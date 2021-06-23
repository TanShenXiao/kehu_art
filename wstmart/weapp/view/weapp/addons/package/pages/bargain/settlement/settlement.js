var http = require('../../../../../utils/request.js');
var util = require('../../../../../utils/util.js');
var app = getApp();
Page({
  /**
   * 页面的初始数据
   */
  data: {
    data: [],
    carts: [],
    shopId: 0,
    addressId: 0,
    areaId2: 0,
    prices: [],
    domain: app.globalData.domain,
    remarks: '',
    payment: [],
    payCodes: [],
    payCode: '',
    paymentWord: '',
    isOnlines: [],
    payType: 0,
    gives: ['快递运输', '自提'],
    givesWord: '快递运输',
    deliverType: 0,
    invoice: false,
    isInvoice: 0,
    invoiceClient: '个人',
    invoiceId: 0,
    isInvoice2: 0,
    invoiceClient2: '个人',
    invoiceId2: 0,
    invoiceCompany: false,
    invoiceHead: '',
    invoiceCode: '',
    invoicelist: [],
    invoiceWord: '不开发票',
    isinvoicelist: false,
    integral: ['是', '否'],
    integralWord: '否',
    isOpenScorePay: 0,
    isUseScore: 0,
    disabled: false,
    tag: true,
    ids: 0,
    currDay:0
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
  },
  /**
  * 生命周期函数--监听小程序显示
  */
  onShow: function (options) {
    this.getData();
    this.setData({
      goodsLogo: app.globalData.confInfo.goodsLogo
    })
  },
  //数据
  getData: function (id) {
    var that = this;
    var addressId = that.data.addressId;
    var areaId2 = that.data.areaId2;
    var paymentWord = app.globalData.paymentWord;
    var payCode = app.globalData.payCode;
    var payType = app.globalData.payType;
    var tokenId = app.globalData.tokenId;
    var sessionId = app.globalData.confInfo.sessionId;
    wx.showLoading({ title: '加载中' });
    http.Post("addon/bargain-carts-weSettlement", { sessionId: sessionId, tokenId: tokenId, addressId: addressId}, function (res) {
      wx.hideLoading();
      if (res.status == 1) {
        var payment = res.data.payNames;
        var payCodes = res.data.payCodes;
        var isOnlines = res.data.isOnlines;
        if (payCodes != '') {
          paymentWord = payment[0];
          payCode = payCodes[0];
          payType = isOnlines[0];
          for (var p in payCodes) {
            if (payCodes[p] == 'weixinpays') {
              paymentWord = payment[p];
              payCode = payCodes[p];
              payType = isOnlines[p];
            }
          }
        }
        if (res.data.userAddress.addressId) {
          addressId = res.data.userAddress.addressId;
          areaId2 = res.data.userAddress.areaId2;
        }
        that.setData({
          data: res.data,
          carts: res.data.carts,
          shopId: res.data.carts.shopId,
          addressId: addressId,
          areaId2: areaId2,
          payment: payment,
          payCodes: payCodes,
          payCode: payCode,
          isOnlines: isOnlines,
          payType: payType,
          paymentWord: paymentWord,
          isOpenScorePay: res.data.isOpenScorePay,
          payments: res.data.payments
        })
        that.getTotal();
      }
    });
  },
  //计算运费/价格
  getTotal: function () {
    var that = this;
    var data = that.data.data;
    var areaId2 = that.data.areaId2;
    var deliverType = that.data.deliverType;
    var isUseScore = that.data.isUseScore;
    var tokenId = app.globalData.tokenId;
    http.Post("addon/bargain-carts-weGetCartMoney", { tokenId: tokenId, deliverType: deliverType, isUseScore: isUseScore, useScore: data.userOrderScore, areaId2: areaId2 }, function (res) {
      if (res.status == 1) {
        that.setData({
          prices: res.data
        })
      }
    });
  },
  
  //店铺
  toshops: function (e) {
    var shopId = e.currentTarget.dataset.shopid;
    wx.navigateTo({
      url: '/pages/shop/shop?shopId=' + shopId
    })
  },
  //商品
  togoods: function (e) {
    var goodsId = e.currentTarget.dataset.goodsid;
    wx.navigateTo({
      url: '/pages/goods-detail/goods-detail?goodsId=' + goodsId
    })
  },
  /*跳转到地址管理 */
  address: function () {
    wx.navigateTo({
      url: '/pages/users/address-mng/address-mng?type=1'
    })
  },
  //备注
  inRemarks: function (e) {
    var that = this;
    var remarks = e.detail.value;
    that.setData({
      remarks: remarks
    })
  },
  //选择
  onChoice: function (e) {
    var that = this;
    var mode = e.currentTarget.dataset.mode;
    var payment = that.data.payment;
    var payCodes = that.data.payCodes;
    var payCode = that.data.payCode;
    var isOnlines = that.data.isOnlines;
    var payType = that.data.payType;
    var integral = that.data.integral;
    if (mode == 'payment') {
      var itemList = payment;
    } else if (mode == 'integral') {
      var itemList = integral;
    }
    wx.showActionSheet({
      itemList: itemList,
      success: function (e) {
        if (e.tapIndex > 0 || e.tapIndex == 0) {
          if (mode == 'payment') {
            that.setData({
              paymentWord: payment[e.tapIndex],
              payCode: payCodes[e.tapIndex],
              payType: isOnlines[e.tapIndex]
            })
          } else if (mode == 'integral') {
            that.setData({
              integralWord: integral[e.tapIndex],
              isUseScore: (e.tapIndex == 1) ? 0 : 1
            })
          }
          that.getTotal();
        }
      }
    })
  },
  //发票
  inInvoice: function (e) {
    var that = this;
    var invoice = e.currentTarget.dataset.invoice;
    if (invoice == 1) {
      that.setData({
        invoice: true,
      })
    } else {
      that.setData({
        invoice: false,
      })
    }
  },
  ifInvoice: function (e) {
    var that = this;
    var value = e.detail.value;
    if (value == 0) {
      that.setData({
        invoiceClient2: '个人',
        invoiceCompany: false
      });
    } else {
      that.setData({
        invoiceClient2: '单位',
        invoiceCompany: true
      });
    }
  },
  invoiceHead: function (e) {
    var that = this;
    that.setData({
      invoiceHead: e.detail.value,
    });
  },
  invoiceHead2: function (e) {
    var that = this;
    var tokenId = app.globalData.tokenId;
    http.Post("weapp/invoices/pageQuery", { tokenId: tokenId }, function (res) {
      if (res.status == 1) {
        that.setData({
          invoicelist: res.data,
          isinvoicelist: true
        });
      }
    });
  },
  invoiceHead3: function (e) {
    var that = this;
    var id = e.currentTarget.dataset.id;
    var invoiceHead = e.currentTarget.dataset.head;
    var invoiceCode = e.currentTarget.dataset.code;
    that.setData({
      invoiceId2: id,
      invoiceHead: invoiceHead,
      invoiceCode: invoiceCode,
      isinvoicelist: false
    });
  },
  invoiceHead4: function () {
    var that = this;
    that.setData({
      isinvoicelist: false
    });
  },
  invoiceCode: function (e) {
    var that = this;
    that.setData({
      invoiceCode: e.detail.value,
    });
  },
  ifInvoice2: function (e) {
    var that = this;
    that.setData({
      isInvoice2: e.detail.value,
    });
  },
  invoice: function () {
    var that = this;
    var isInvoice2 = that.data.isInvoice2;
    var invoiceClient2 = that.data.invoiceClient2;
    var invoiceId2 = that.data.invoiceId2;
    var invoiceHead = that.data.invoiceHead;
    var invoiceCode = that.data.invoiceCode;
    var tokenId = app.globalData.tokenId;
    if (isInvoice2 == 1) {
      if (invoiceClient2 != '个人') {
        if (invoiceHead == '') {
          app.prompt('请输入发票抬头');
          return false;
        }
        if (invoiceId2 > 0) {
          var url = 'weapp/invoices/edit';
          var data = { tokenId: tokenId, invoiceHead: invoiceHead, invoiceCode: invoiceCode, id: invoiceId2 };
        } else {
          var url = 'weapp/invoices/add';
          var data = { tokenId: tokenId, invoiceHead: invoiceHead, invoiceCode: invoiceCode };
        }
        http.Post(url, data, function (res) {
          if (res.status == 1) {
            if (invoiceId2 > 0) {
              that.setData({ invoiceId: invoiceId2 });
            } else {
              that.setData({ invoiceId2: res.data.id, invoiceId: res.data.id, });
            }
            that.setData({
              invoiceWord: '普通发票（纸质） ' + invoiceHead + ' 明细',
              invoiceClient: invoiceHead,
              isInvoice: isInvoice2
            });
          } else {
            app.prompt(res.msg);
          }
        });
      } else {
        that.setData({
          invoiceWord: '普通发票（纸质） 个人 明细',
          invoiceId: 1,
          invoiceClient: invoiceClient2,
          isInvoice: isInvoice2
        });
      }
    }
    that.setData({ invoice: false });
  },
  //结算
  submit: function () {
    var that = this;
    var data = that.data.data;
    var shopId = that.data.shopId;
    var addressId = that.data.addressId;
    var remarks = that.data.remarks;
    var payType = that.data.payType;
    var payCode = that.data.payCode;
    var deliverType = that.data.deliverType;
    var prices = that.data.prices;
    var isUseScore = that.data.isUseScore;
    var isInvoice = that.data.isInvoice;
    var invoiceId = that.data.invoiceId;
    var invoiceClient = that.data.invoiceClient;
    var tokenId = app.globalData.tokenId;
    if (deliverType == 0 && addressId == 0) {
      app.prompt('请选择收货地址');
      return false;
    }
    that.setData({ disabled: true })
    wx.showLoading({ title: '提交中···' });
    var datas = new Array();
    datas['tokenId'] = tokenId;
    datas['shopId'] = shopId;
    datas['s_addressId'] = addressId;
    datas['payType'] = payType;
    datas['payCode'] = payCode;
    datas['deliverType'] = deliverType;
    datas['isUseScore'] = isUseScore;
    datas['useScore'] = prices.maxScore;
    datas['isInvoice'] = isInvoice;
    datas['invoiceId'] = invoiceId;
    datas['invoiceClient'] = invoiceClient;
    datas['remark'] = remarks;
    http.Post("addon/bargain-carts-weSubmit", datas, function (res) {
      wx.hideLoading();
      if (res.status == 1) {
        if (payType == 1 && prices.realTotalMoney > 0) {
          wx.showToast({
            title: res.msg,
            icon: 'success',
            complete: function (err) {
              if (payCode == 'weixinpays' || payCode == '') {
                wx.redirectTo({
                  url: '/pages/users/payment/weixinpays/weixinpays?orderNo=' + res.data + '&isBatch=1'
                })
              } else if (payCode == 'wallets') {
                wx.redirectTo({
                  url: '/pages/users/payment/wallets/wallets?orderNo=' + res.data + '&isBatch=1'
                })
              }
            }
          })
        } else {
          wx.showToast({
            title: res.msg,
            icon: 'success',
            complete: function (err) {
              app.globalData.ordersType = '';
              wx.switchTab({
                url: '/pages/users/orders/orders',
              })
            }
          })
        }
      } else {
        app.prompt(res.msg);
        that.setData({ disabled: false })
      }
    });
  }
})