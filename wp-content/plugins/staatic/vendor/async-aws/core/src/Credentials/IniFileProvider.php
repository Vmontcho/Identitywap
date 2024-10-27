<?php

declare (strict_types=1);
namespace Staatic\Vendor\AsyncAws\Core\Credentials;

use Exception;
use DateTimeImmutable;
use Staatic\Vendor\AsyncAws\Core\Configuration;
use Staatic\Vendor\AsyncAws\Core\Exception\RuntimeException;
use Staatic\Vendor\AsyncAws\Core\Sts\StsClient;
use Staatic\Vendor\AsyncAws\Sso\SsoClient;
use Staatic\Vendor\Psr\Log\LoggerInterface;
use Staatic\Vendor\Psr\Log\NullLogger;
use Staatic\Vendor\Symfony\Contracts\HttpClient\HttpClientInterface;
final class IniFileProvider implements CredentialProvider
{
    use DateFromResult;
    private $iniFileLoader;
    private $logger;
    private $httpClient;
    public function __construct(?LoggerInterface $logger = null, ?IniFileLoader $iniFileLoader = null, ?HttpClientInterface $httpClient = null)
    {
        $this->logger = $logger ?? new NullLogger();
        $this->iniFileLoader = $iniFileLoader ?? new IniFileLoader($this->logger);
        $this->httpClient = $httpClient;
    }
    /**
     * @param Configuration $configuration
     */
    public function getCredentials($configuration): ?Credentials
    {
        $profilesData = $this->iniFileLoader->loadProfiles([$configuration->get(Configuration::OPTION_SHARED_CREDENTIALS_FILE), $configuration->get(Configuration::OPTION_SHARED_CONFIG_FILE)]);
        if (empty($profilesData)) {
            return null;
        }
        $profile = $configuration->get(Configuration::OPTION_PROFILE);
        return $this->getCredentialsFromProfile($profilesData, $profile);
    }
    private function getCredentialsFromProfile(array $profilesData, string $profile, array $circularCollector = []): ?Credentials
    {
        if (isset($circularCollector[$profile])) {
            $this->logger->warning('Circular reference detected when loading "{profile}". Already loaded {previous_profiles}', ['profile' => $profile, 'previous_profiles' => array_keys($circularCollector)]);
            return null;
        }
        $circularCollector[$profile] = \true;
        if (!isset($profilesData[$profile])) {
            $this->logger->warning('Profile "{profile}" not found.', ['profile' => $profile]);
            return null;
        }
        $profileData = $profilesData[$profile];
        if (isset($profileData[IniFileLoader::KEY_ACCESS_KEY_ID], $profileData[IniFileLoader::KEY_SECRET_ACCESS_KEY])) {
            return new Credentials($profileData[IniFileLoader::KEY_ACCESS_KEY_ID], $profileData[IniFileLoader::KEY_SECRET_ACCESS_KEY], $profileData[IniFileLoader::KEY_SESSION_TOKEN] ?? null);
        }
        if (isset($profileData[IniFileLoader::KEY_ROLE_ARN])) {
            return $this->getCredentialsFromRole($profilesData, $profileData, $profile, $circularCollector);
        }
        if (isset($profileData[IniFileLoader::KEY_SSO_START_URL])) {
            if (class_exists(SsoClient::class)) {
                return $this->getCredentialsFromLegacySso($profileData, $profile);
            }
            $this->logger->warning('The profile "{profile}" contains SSO (legacy) config but the "async-aws/sso" package is not installed. Try running "composer require async-aws/sso".', ['profile' => $profile]);
            return null;
        }
        $this->logger->info('No credentials found for profile "{profile}".', ['profile' => $profile]);
        return null;
    }
    private function getCredentialsFromRole(array $profilesData, array $profileData, string $profile, array $circularCollector = []): ?Credentials
    {
        $roleArn = (string) ($profileData[IniFileLoader::KEY_ROLE_ARN] ?? '');
        $roleSessionName = (string) ($profileData[IniFileLoader::KEY_ROLE_SESSION_NAME] ?? uniqid('async-aws-', \true));
        if (null === $sourceProfileName = $profileData[IniFileLoader::KEY_SOURCE_PROFILE] ?? null) {
            $this->logger->warning('The source profile is not defined in Role "{profile}".', ['profile' => $profile]);
            return null;
        }
        $sourceCredentials = $this->getCredentialsFromProfile($profilesData, $sourceProfileName, $circularCollector);
        if (null === $sourceCredentials) {
            $this->logger->warning('The source profile "{profile}" does not contains valid credentials.', ['profile' => $profile]);
            return null;
        }
        $stsClient = new StsClient(isset($profilesData[$sourceProfileName][IniFileLoader::KEY_REGION]) ? ['region' => $profilesData[$sourceProfileName][IniFileLoader::KEY_REGION]] : [], $sourceCredentials, $this->httpClient);
        $result = $stsClient->assumeRole(['RoleArn' => $roleArn, 'RoleSessionName' => $roleSessionName]);
        try {
            if (null === $credentials = $result->getCredentials()) {
                throw new RuntimeException('The AssumeRole response does not contains credentials');
            }
        } catch (Exception $e) {
            $this->logger->warning('Failed to get credentials from assumed role in profile "{profile}: {exception}".', ['profile' => $profile, 'exception' => $e]);
            return null;
        }
        return new Credentials($credentials->getAccessKeyId(), $credentials->getSecretAccessKey(), $credentials->getSessionToken(), Credentials::adjustExpireDate($credentials->getExpiration(), $this->getDateFromResult($result)));
    }
    private function getCredentialsFromLegacySso(array $profileData, string $profile): ?Credentials
    {
        if (!isset($profileData[IniFileLoader::KEY_SSO_START_URL], $profileData[IniFileLoader::KEY_SSO_REGION], $profileData[IniFileLoader::KEY_SSO_ACCOUNT_ID], $profileData[IniFileLoader::KEY_SSO_ROLE_NAME])) {
            $this->logger->warning('Profile "{profile}" does not contains required legacy SSO config.', ['profile' => $profile]);
            return null;
        }
        $ssoCacheFileLoader = new SsoCacheFileLoader($this->logger);
        $tokenData = $ssoCacheFileLoader->loadSsoCacheFile($profileData[IniFileLoader::KEY_SSO_START_URL]);
        if ([] === $tokenData) {
            return null;
        }
        $ssoClient = new SsoClient(['region' => $profileData[IniFileLoader::KEY_SSO_REGION]], new NullProvider(), $this->httpClient);
        $result = $ssoClient->getRoleCredentials(['accessToken' => $tokenData[SsoCacheFileLoader::KEY_ACCESS_TOKEN], 'accountId' => $profileData[IniFileLoader::KEY_SSO_ACCOUNT_ID], 'roleName' => $profileData[IniFileLoader::KEY_SSO_ROLE_NAME]]);
        try {
            if (null === $credentials = $result->getRoleCredentials()) {
                throw new RuntimeException('The RoleCredentials response does not contains credentials');
            }
            if (null === $accessKeyId = $credentials->getAccessKeyId()) {
                throw new RuntimeException('The RoleCredentials response does not contain an accessKeyId');
            }
            if (null === $secretAccessKey = $credentials->getSecretAccessKey()) {
                throw new RuntimeException('The RoleCredentials response does not contain a secretAccessKey');
            }
            if (null === $sessionToken = $credentials->getSessionToken()) {
                throw new RuntimeException('The RoleCredentials response does not contain a sessionToken');
            }
            if (null === $expiration = $credentials->getExpiration()) {
                throw new RuntimeException('The RoleCredentials response does not contain an expiration');
            }
        } catch (Exception $e) {
            $this->logger->warning('Failed to get credentials from role credentials in profile "{profile}: {exception}".', ['profile' => $profile, 'exception' => $e]);
            return null;
        }
        return new Credentials($accessKeyId, $secretAccessKey, $sessionToken, (new DateTimeImmutable())->setTimestamp($expiration));
    }
}
