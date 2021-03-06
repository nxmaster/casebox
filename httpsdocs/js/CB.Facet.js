Ext.namespace('CB');
CB.Facet = Ext.extend( Ext.Panel, {
    title: 'facet'
    ,height: 100
    ,collapsible: true
    ,titleCollapse: true
    ,hideCollapseTool: true
    ,cls: 'facet'
    ,border: false
    ,mode: 'OR'
    ,modeToggle: false
    ,bodyStyle: 'background: none'
    ,initComponent: function(){
        var tools = [];
        if(this.manualPeriod) {
            tools.push({
                id: 'period'
                ,name: 'period'
                // ,text: 'Period'
                ,xtype: 'button'
                // ,html: '<span onclick="document.getElementById(\'addDatePeriod\').style.display = \'block\'" style="cursor: pointer; padding-left: 19px; background: url(/i4/16/calendar_mono.png) 0px 0px no-repeat; vertical-align: top;">Period</span>'
                ,handler: this.onPeriodAddClick
                ,scope: this
                ,qtip: 'Add period'
            });
        }

        if(this.modeToggle) {
            tools.push({
                id: 'unchain'
                ,name: 'unchain'
                ,handler: this.onModeToggle
                ,scope: this
                ,qtip: L.searchSwitchModeMessage
            });
        }


        Ext.apply(
            this
            ,{
                tools: tools
            }
        );

        CB.Facet.superclass.initComponent.apply(this, arguments);
        this.addEvents('facetchange', 'modechange');
        this.enableBubble(['facetchange', 'modechange']);
    }
    ,setModeVisible: function(visible){
        if(!this.rendered) {
            return;
        }
        this.getEl().removeClass('multivalued');
        if(visible) {
            this.getEl().addClass('multivalued');
        }
    }
    ,onModeToggle: function(ev, toolEl, panel, tc){
        if (toolEl.hasClass('x-tool-chain')) {
            toolEl.replaceClass('x-tool-chain', 'x-tool-unchain');
            this.mode = 'OR';
        } else {
            toolEl.replaceClass('x-tool-unchain', 'x-tool-chain');
            this.mode = 'AND';
        }
        this.fireEvent('modechange', this, ev);
    }
    // ,onPeriodAddClick: function(ev, el, panel, tc) {
    //     var coord = el.getXY();
    //     var w = new Ext.Panel({
    //         title: 'Date select'
    //         ,floating: true
    //         ,closable: true
    //         ,width: 100
    //         ,height: 50
    //         ,items: [{
    //             xtype: 'label'
    //             ,text: 'Date'
    //         }]
    //         ,renderTo: Ext.getBody()
    //     });
    //     w.setPosition(coord[0]-100,coord[1]);
    //     w.show();
    // }
}
);

Ext.reg('CBFacet', CB.Facet);
