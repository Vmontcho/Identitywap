<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1" name="viewport">
<?php wp_enqueue_style('normalize', get_template_directory_uri() . '/assets/css/normalize.css', [], '1719838006'); ?>
<?php wp_enqueue_style('webflow', get_template_directory_uri() . '/assets/css/webflow.css', [], '1719838006'); ?>
<?php wp_enqueue_style('identity-wapwebflow', get_template_directory_uri() . '/assets/css/identity-wap.webflow.css', [], '1719838006'); ?>
<link href="https://fonts.googleapis.com" rel="preconnect">
<link href="https://fonts.gstatic.com" rel="preconnect" crossorigin="anonymous">
<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js" type="text/javascript"></script>
<script type="text/javascript">WebFont.load({
google: {
families: ["Playfair Display:regular,500,600,700,800,900","DM Serif Display:regular","Abril Fatface:regular","Cinzel:regular,500,600,700,800,900","Josefin Slab:100,200,300,regular,500,600,700","Baskervville:regular"]
}});</script>
<script type="text/javascript">!function(o,c){var n=c.documentElement,t=" w-mod-";n.className+=t+"js",("ontouchstart"in o||o.DocumentTouch&&c instanceof DocumentTouch)&&(n.className+=t+"touch")}(window,document);</script>
<link href="<?php echo get_template_directory_uri(); ?>/assets/images/favicon.ico?v=1719838006" rel="shortcut icon" type="image/x-icon">
<link href="<?php echo get_template_directory_uri(); ?>/assets/images/webclip.png?v=1719838006" rel="apple-touch-icon">
<style>
/*width*/
#scrollbar::-webkit-scrollbar {
width:0px;
height: 0px;
}
/* Small devices (portrait tablets and large phones, 600px and up) */
@media only screen and (max-width: 600px) {
#scrollbar::-webkit-scrollbar { width:0px; height: 0px;}
}
/*track*/
#scrollbar::-webkit-scrollbar-track {
background:rgba(255, 255, 255, 0);
border-radius:0px;
}
/*thumb*/
#scrollbar::-webkit-scrollbar-thumb {
background: rgba(255, 255, 255, 0);
border-radius:0px;
}
#scrollbar::-webkit-scrollbar-thumb:hover {
background: rgba(255, 255, 255, 0);
}
</style>