var http = require('../../../../utils/request.js');
var app = getApp();
Page({
  /**
   * 页面的初始数据
   */
  data: {
    expressType:0,
    expressId:0,
    expressNo:'',
    expressArr:[],
    index:0
  }, 
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.setData({
      goodsLogo: app.globalData.confInfo.goodsLogo,
      resourceDomain:app.globalData.resourceDomain,
      id:options.id
    });
  },
  
  /**
  * 生命周期函数--监听小程序显示
  */
  onShow: function () {
    var that = this;
    that.setData({
      detail: {}
    });
    that.getDate();
  },
  //数据
  getDate: function () {
    var that = this;
    var tokenId = app.globalData.tokenId;
    var id = this.data.id;
    http.Post("weapp/orderservices/sendPage", { tokenId: tokenId, id:id }, function (res) {
      if (res.status == 1) {
        var { express, detail } = res.data;
        let expressArr = express.map(x=>x.expressName);
        expressArr.unshift('请选择');
        that.setData({
          expressArr,
          express
        })
      }
    });
  },
  changetType: function ({ currentTarget: { dataset: { type } } }) {
    this.setData({expressType:type})
  },
  bindPickerChange:function({detail:{value}}){
    const expressId = this.data.express[value].expressId;
    this.setData({index:value, expressId});
  },
  bindInput: function ({ detail: { value } }) {
    this.setData({expressNo:value})
  },
  send:function(){
    const { id, expressType, expressId, expressNo  } = this.data; 
    let postData = {
        id,
        expressType, 
        expressId, 
        expressNo,
        tokenId:app.globalData.tokenId
    };
    if(expressId==0){
        return app.prompt('请选择物流公司');
    }
    if(expressNo==''){
        return app.prompt('请输入物流单号');
    }
    http.Post("weapp/orderservices/userExpress", postData, function (res) {
      app.prompt(res.msg);
      if(res.status==1){
        wx.navigateBack();
      }
    });
  },
})