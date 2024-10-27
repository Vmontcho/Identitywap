(function($){

    function init() {
        var Field = acf.Field.extend({

            type: 'set',
            wait: '',

            events: {
                'click a[data-event="add-row"]': 		'onClickAdd',
                'click a[data-event="duplicate-row"]':	'onClickDuplicate',
                'click a[data-event="remove-row"]': 	'onClickRemove',
                'click a[data-event="collapse-row"]': 	'onClickCollapse',
                'showField':							'onShow',
                'unloadField':							'onUnload',
                'mouseover': 							'onHover',
                'unloadField':							'onUnload'
            },

            $control: function(){
                return this.$('.acf-set:first');
            },

            $table: function(){
                return this.$('table:first');
            },

            $tbody: function(){
                return this.$('tbody:first');
            },

            $rows: function(){
                return this.$('tbody:first > tr').not('.acf-clone');
            },

            $row: function( index ){
                return this.$('tbody:first > tr:eq(' + index + ')');
            },

            $clone: function(){
                return this.$('tbody:first > tr.acf-clone');
            },

            $actions: function(){
                return this.$('.acf-actions:last');
            },

            $button: function(){
                return this.$('.acf-actions:last .button');
            },

            getValue: function(){
                return this.$rows().length;
            },

            allowRemove: function(){
                var min = parseInt( this.get('min') );
                return ( !min || min < this.val() );
            },

            allowAdd: function(){
                var max = parseInt( this.get('max') );
                return ( !max || max > this.val() );
            },

            addSortable: function( self ){

                // bail early if max 1 row
                if( this.get('max') == 1 ) {
                    return;
                }

                // add sortable
                this.$tbody().sortable({
                    items: '> tr',
                    handle: '> td.order',
                    forceHelperSize: true,
                    forcePlaceholderSize: true,
                    scroll: true,
                    stop: function(event, ui) {
                        self.render();
                    },
                    update: function(event, ui) {
                        self.$input().trigger('change');
                    }
                });
            },

            addCollapsed: function(){

                // vars
                var indexes = preference.load( this.get('key') );

                // bail early if no collapsed
                if( !indexes ) {
                    return false;
                }

                // loop
                this.$rows().each(function( i ){
                    if( indexes.indexOf(i) > -1 ) {
                        $(this).addClass('-collapsed');
                    }
                });
            },

            addUnscopedEvents: function( self ){

                // invalidField
                this.on('invalidField', '.acf-row', function(e){
                    var $row = $(this);
                    if( self.isCollapsed($row) ) {
                        self.expand( $row );
                    }
                });
            },

            initialize: function(){

                // add unscoped events
                this.addUnscopedEvents( this );

                // add collapsed
                this.addCollapsed();

                // disable clone
                acf.disable( this.$clone(), this.cid );

                // render
                this.render();
            },

            render: function(){

                window.test = this;

                // update order number
                this.$rows().each(function( i ){
                    $(this).find('> .order > span').html( i+1 );
                });

                // Extract vars.
                var $controll = this.$control();
                var $button = this.$button();

                // empty
                if( this.val() == 0 ) {
                    $controll.addClass('-empty');
                } else {
                    $controll.removeClass('-empty');
                }

                // Reached max rows.
                if( !this.allowAdd() ) {
                    $controll.addClass('-max');
                    $button.addClass('disabled');
                } else {
                    $controll.removeClass('-max');
                    $button.removeClass('disabled');
                }

                // Reached min rows (not used).
                //if( !this.allowRemove() ) {
                //	$controll.addClass('-min');
                //} else {
                //	$controll.removeClass('-min');
                //}
            },

            validateAdd: function(){

                // return true if allowed
                if( this.allowAdd() ) {
                    return true;
                }

                // vars
                var max = this.get('max');
                var text = acf.__('Maximum rows reached ({max} rows)');

                // replace
                text = text.replace('{max}', max);

                // add notice
                this.showNotice({
                    text: text,
                    type: 'warning'
                });

                // return
                return false;
            },

            onClickAdd: function( e, $el ){

                // validate
                if( !this.validateAdd() ) {
                    return false;
                }

                // add above row
                if( $el.hasClass('acf-icon') ) {
                    this.add({
                        before: $el.closest('.acf-row')
                    });

                    // default
                } else {
                    this.add();
                }
            },

            add: function( args ){

                // validate
                if( !this.allowAdd() ) {
                    return false;
                }

                // defaults
                args = acf.parseArgs(args, {
                    before: false
                });

                // add row
                var $el = acf.duplicate({
                    target: this.$clone(),
                    append: this.proxy(function( $el, $el2 ){

                        // append
                        if( args.before ) {
                            args.before.before( $el2 );
                        } else {
                            $el.before( $el2 );
                        }

                        // remove clone class
                        $el2.removeClass('acf-clone');

                        // enable
                        acf.enable( $el2, this.cid );

                        // render
                        this.render();
                    })
                });

                // trigger change for validation errors
                this.$input().trigger('change');

                // return
                return $el;
            },

            onClickDuplicate: function( e, $el ){

                // Validate with warning.
                if( !this.validateAdd() ) {
                    return false;
                }

                // get layout and duplicate it.
                var $row = $el.closest('.acf-row');
                this.duplicateRow( $row );
            },

            duplicateRow: function( $row ){

                // Validate without warning.
                if( !this.allowAdd() ) {
                    return false;
                }

                // Vars.
                var fieldKey = this.get('key');

                // Duplicate row.
                var $el = acf.duplicate({
                    target: $row,

                    // Provide a custom renaming callback to avoid renaming parent row attributes.
                    rename: function( name, value, search, replace ){

                        // Rename id attributes from "field_1-search" to "field_1-replace".
                        if( name === 'id' ) {
                            return value.replace( fieldKey + '-' + search, fieldKey + '-' + replace );

                            // Rename name and for attributes from "[field_1][search]" to "[field_1][replace]".
                        } else {
                            return value.replace( fieldKey + '][' + search, fieldKey + '][' + replace );
                        }
                    },
                    before: function( $el ){
                        acf.doAction('unmount', $el);
                    },
                    after: function( $el, $el2 ){
                        acf.doAction('remount', $el);
                    },
                });

                // trigger change for validation errors
                this.$input().trigger('change');

                // Update order numbers.
                this.render();

                // Focus on new row.
                acf.focusAttention( $el );

                // Return new layout.
                return $el;
            },

            validateRemove: function(){

                // return true if allowed
                if( this.allowRemove() ) {
                    return true;
                }

                // vars
                var min = this.get('min');
                var text = acf.__('Minimum rows reached ({min} rows)');

                // replace
                text = text.replace('{min}', min);

                // add notice
                this.showNotice({
                    text: text,
                    type: 'warning'
                });

                // return
                return false;
            },

            onClickRemove: function( e, $el ){
                var $row = $el.closest('.acf-row');

                // Bypass confirmation when holding down "shift" key.
                if( e.shiftKey ) {
                    return this.remove( $row );
                }

                // add class
                $row.addClass('-hover');

                // add tooltip
                var tooltip = acf.newTooltip({
                    confirmRemove: true,
                    target: $el,
                    context: this,
                    confirm: function(){
                        this.remove( $row );
                    },
                    cancel: function(){
                        $row.removeClass('-hover');
                    }
                });
            },

            remove: function( $row ){

                // reference
                var self = this;

                // remove
                acf.remove({
                    target: $row,
                    endHeight: 0,
                    complete: function(){

                        // trigger change to allow attachment save
                        self.$input().trigger('change');

                        // render
                        self.render();

                        // sync collapsed order
                        //self.sync();
                    }
                });
            },

            isCollapsed: function( $row ){
                return $row.hasClass('-collapsed');
            },

            collapse: function( $row ){
                $row.addClass('-collapsed');
                acf.doAction('hide', $row, 'collapse');
            },

            expand: function( $row ){
                $row.removeClass('-collapsed');
                acf.doAction('show', $row, 'collapse');
            },

            onClickCollapse: function( e, $el ){

                // vars
                var $row = $el.closest('.acf-row');
                var isCollpased = this.isCollapsed( $row );

                // shift
                if( e.shiftKey ) {
                    $row = this.$rows();
                }

                // toggle
                if( isCollpased ) {
                    this.expand( $row );
                } else {
                    this.collapse( $row );
                }
            },

            onShow: function( e, $el, context ){

                console.log(e, $el, context);

                // get sub fields
                var fields = acf.getFields({
                    is: ':visible',
                    parent: this.$el,
                });

                // trigger action
                // - ignore context, no need to pass through 'conditional_logic'
                // - this is just for fields like google_map to render itself
                acf.doAction('show_fields', fields);
            },

            onUnload: function(){

                // vars
                var indexes = [];

                // loop
                this.$rows().each(function( i ){
                    if( $(this).hasClass('-collapsed') ) {
                        indexes.push( i );
                    }
                });

                // allow null
                indexes = indexes.length ? indexes : null;

                // set
                preference.save( this.get('key'), indexes );
            },

            onHover: function(){

                // add sortable
                this.addSortable( this );

                // remove event
                this.off('mouseover');
            }
        });

        acf.registerFieldType( Field );


        // register existing conditions
        acf.registerConditionForFieldType('hasValue', 'set');
        acf.registerConditionForFieldType('hasNoValue', 'set');
        acf.registerConditionForFieldType('lessThan', 'set');
        acf.registerConditionForFieldType('greaterThan', 'set');


        // state
        var preference = new acf.Model({

            name: 'this.collapsedRows',

            key: function( key, context ){

                // vars
                var count = this.get(key+context) || 0;

                // update
                count++;
                this.set(key+context, count, true);

                // modify fieldKey
                if( count > 1 ) {
                    key += '-' + count;
                }

                // return
                return key;
            },

            load: function( key ){

                // vars
                var key = this.key(key, 'load');
                var data = acf.getPreference(this.name);


                // return
                if( data && data[key] ) {
                    return data[key]
                } else {
                    return false;
                }
            },

            save: function( key, value ){

                // vars
                var key = this.key(key, 'save');
                var data = acf.getPreference(this.name) || {};

                // delete
                if( value === null ) {
                    delete data[ key ];

                    // append
                } else {
                    data[ key ] = value;
                }

                // allow null
                if( $.isEmptyObject(data) ) {
                    data = null;
                }

                // save
                acf.setPreference(this.name, data);
            }
        });
    }

    init();

    $(document).on('ajaxComplete', function() {
        acf.do_action('append', $('#post'));
    });


})(jQuery);