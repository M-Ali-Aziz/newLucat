pimcore.registerNS("pimcore.plugin.NewLucatBundle");

pimcore.plugin.NewLucatBundle = Class.create(pimcore.plugin.admin, {
    getClassName: function () {
        return "pimcore.plugin.NewLucatBundle";
    },

    initialize: function () {
        pimcore.plugin.broker.registerPlugin(this);
    },

    pimcoreReady: function (params, broker) {
        // alert("NewLucatBundle ready!");
    }
});

var NewLucatBundlePlugin = new pimcore.plugin.NewLucatBundle();
