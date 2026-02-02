var Smushit = function (config) {
    config = config || {};
    Smushit.superclass.constructor.call(this, config);
};
Ext.extend(Smushit, Ext.Component, {
    page: {},
    window: {},
    grid: {},
    tree: {},
    panel: {},
    combo: {},
    config: {},
});
Ext.reg("smushit", Smushit);
Smushit = new Smushit();