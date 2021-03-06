var http = require('../../../../utils/request.js');
var app = getApp();
Page({
  /**
   * 页面的初始数据
   */
  data: {
    data: [],
    goodsLogo: null,
    domain: app.globalData.domain
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.getData(options.orderId, options.types);
    this.setData({
      orderId:options.orderId,
      resourceDomain:app.globalData.resourceDomain,
      goodsLogo: app.globalData.confInfo.goodsLogo
    })
  },
  //列表
  getData: function (id, types) {
    var that = this;
    var tokenId = app.globalData.tokenId;
    http.Post("weapp/orders/getDetail", { tokenId: tokenId, id: id, types: types}, function (res) {
      if (res.status == 1) {
        that.setData({
          data: res.data
        })
      }else{
        wx.showModal({
          title: '提示',
          content: res.msg,
          showCancel: false,
          confirmText: "确定",
          success: function (res) {
            if (res.confirm) {
              wx.navigateBack({
                delta: 1
              })
            }
          }
        })
      }
    });
  },
  //店铺
  toshops: function (e) {
    var shopId = e.currentTarget.dataset.shopid;
    if (shopId == 1) {
      wx.navigateTo({
        url: '../../../shop-self/shop-self'
      })
    } else {
      wx.navigateTo({
        url: '../../../shop-home/shop-home?shopId=' + shopId
      })
    }
  },
  addCart: function ({ currentTarget: { dataset: { goodsid:goodsId, goodsspecId:goodsSpecId } } }) {
    if (goodsId == '') {
      app.prompt('请选择商品加入购物车');
      return;
    }
    // 请求接口
    var tokenId = app.globalData.tokenId;
    let postData = {
      goodsId,
      goodsSpecId,
      buyNum: 1,
      tokenId
    }
    http.Post('weapp/carts/addCart', postData, function (res) {
      app.prompt(res.msg);
    });
  }
})