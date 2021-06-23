var http = require('../../../../../utils/request.js');
var app = getApp();
Page({
  /**
   * 页面的初始数据
   */
  data: {
    data:[],
    addressId:0,
    areaId2: 0,
    price:[],
    goodsLogo: null,
    domain: app.globalData.domain,
    shopRemarks:[],
    isOnlines:[],
    gives: ['快递运输','自提'],
    givesWord:'快递运输',
    deliverType:0,
    invoice: false,
    isInvoice: 0,
    invoiceClient: '个人',
    invoiceId: 0,
    isInvoice2: 0,
    invoiceClient2: '个人',
    invoiceId2: 0,
    invoiceCompany:false,
    invoiceHead:'',
    invoiceCode:'',
    invoicelist:[],
    invoiceWord: '不开发票',
    isinvoicelist:false,
    disabled:false,
    tag:true,
    defaults:'填写订单备注'
  },
  onLoad: function (options) {
    this.setData({id:options.id});
  },
  /**
  * 生命周期函数--监听小程序显示
  */
  onShow: function () {
    this.getData();
    this.setData({
      goodsLogo: app.globalData.confInfo.goodsLogo,
    })
  },
  //数据
  getData:function(){
    var that = this;
    var addressId = that.data.addressId;
    var areaId2 = that.data.areaId2;
    var tokenId = app.globalData.tokenId;
    let id = that.data.id;
    const postData = { 
      tokenId: tokenId, 
      addressId: addressId,
      id:id,
    };
    http.Post("addon/auction-weapps-checkPayStatus", postData, function (res) {
      if (res.status == 1) {
        if (res.data.userAddress.addressId){
          addressId = res.data.userAddress.addressId;
          areaId2 = res.data.userAddress.areaId2;
        }
        that.setData({
          data: res.data,
          resourceDomain:app.globalData.resourceDomain,
          addressId: addressId,
          areaId2: areaId2,
        })
      }
    });
  },
  /*跳转到地址管理 */
  address:function (){
    wx.navigateTo({
      url: '../../../../../pages/users/address-mng/address-mng?type=1'
    })
  },
  //备注
  inRemarks:function(e){
    var that = this;
    var value = e.detail.value;
    that.setData({
      defaults: value
    })
  },
  //选择
  onChoice: function (e) {
    var that = this;
    var mode = e.currentTarget.dataset.mode; 
    var gives = that.data.gives;
    if (mode == 'gives'){
      var itemList =  gives;
    }
    wx.showActionSheet({
      itemList: itemList,
      success: function (e) {
        if (e.tapIndex > 0 || e.tapIndex == 0){
          if (mode == 'gives') {
            that.setData({
              givesWord: gives[e.tapIndex],
              deliverType: e.tapIndex
            })
          }
          that.getTotal();
        }
      }
    })
  },
  //发票
  inInvoice:function(e){
    var that = this;
    var invoice = e.currentTarget.dataset.invoice;
    if (invoice==1){
      that.setData({
        invoice: true,
      })
    }else{
      that.setData({
        invoice: false,
      })
    }
  },
  ifInvoice: function (e) {
    var that = this;
    var value = e.detail.value;
    if (value==0){
      that.setData({
        invoiceClient2:'个人',
        invoiceCompany:false
      });
    }else{
      that.setData({
        invoiceClient2: '单位',
        invoiceCompany:true
      });
    } 
  },
  invoiceHead: function (e) {
    var that = this;
    that.setData({
      invoiceHead: e.detail.value,
    });
  },
  invoiceHead2:function(e){
    var that = this;
    var tokenId = app.globalData.tokenId;
    http.Post("weapp/invoices/pageQuery", { tokenId: tokenId}, function (res) {
      if (res.status == 1) {
        that.setData({
          invoicelist: res.data,
          isinvoicelist:true
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
      invoiceId2:id,
      invoiceHead: invoiceHead,
      invoiceCode: invoiceCode,
      isinvoicelist: false
    });
  },
  invoiceHead4: function(){
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
  invoice:function(){
    var that = this;
    var isInvoice2 = that.data.isInvoice2;
    var invoiceClient2 = that.data.invoiceClient2;
    var invoiceId2 = that.data.invoiceId2;
    var invoiceHead = that.data.invoiceHead;
    var invoiceCode = that.data.invoiceCode;
    var tokenId = app.globalData.tokenId;
    if (isInvoice2==1){
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
    that.setData({invoice: false});
  },
  //结算
  submit:function(){
    var that = this;
    var data = that.data.data;
    var addressId = that.data.addressId;
    var deliverType = that.data.deliverType;
    var price = that.data.price;
    var isInvoice = that.data.isInvoice;
    var invoiceId = that.data.invoiceId;
    var invoiceClient = that.data.invoiceClient;
    var tokenId = app.globalData.tokenId;
    var defaults = that.data.defaults;
    if (defaults == '填写订单备注'){
      this.setData({
        defaults:''
      })
    }
    if (deliverType == 0 && addressId == 0) {
      app.prompt('请选择收货地址');
      return false;
    }
    that.setData({ disabled: true })
    wx.showLoading({ title: '提交中···' });
    var datas = new Array();
    datas['tokenId'] = tokenId;
    datas['s_addressId'] = addressId;
    datas['deliverType'] = deliverType;
    datas['useScore'] = price.maxScore;
    datas['isInvoice'] = isInvoice;
    datas['invoiceId'] = invoiceId;
    datas['invoiceClient'] = invoiceClient;
    datas['remark'] = defaults;
    datas['auctionId'] = that.data.id;
    http.Post("addon/auction-weapps-submit", datas, function (res) {
      wx.hideLoading();
      if (res.status == 1) {
          wx.showToast({
            title: res.msg,
            icon: 'success',
            complete: function (err) {
              wx.redirectTo({
                url: '../../../../../pages/users/orders/orders',
              })
            }
          })
        
      } else {
        app.prompt(res.msg);
        that.setData({ disabled: false })
      }
    });
  },
  focus:function(){
    this.setData({
      defaults:''
    })
  },
  loseFocus:function(){
    wx.setTopBarText({
      text: 'hello, world!'
    })
    if (this.data.shopRemarks == ''){
      this.setData({
        defaults: '填写订单备注'
      })
    }
  }
})