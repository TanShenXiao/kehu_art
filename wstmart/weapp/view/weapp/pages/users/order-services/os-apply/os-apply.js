var http = require('../../../../utils/request.js');
var app = getApp();
Page({
  /**
   * 页面的初始数据
   */
  data: {
    goodsLogo: null,
    domain: app.globalData.domain,
    goods: [],
    // 是否打开drawer
    openDrawer:false,
    // 当前打开的页面
    currOpt:'goodsServiceType',
    optJson:{
      goodsServiceType:{
        title:'售后类型',
        value:0,
        currText:'退款退货',
        options:[{value:0,text:'退款退货',isCheck:true},
                 {value:1,text:'退款',isCheck:false},
                 {value:2,text:'换货',isCheck:false}]
      },
      serviceType:{
        title:'申请原因',
        value:-1,
        currText:'请选择',
        options:[] // 从服务器请求
      } 
    },
    // 可退款金额
    canRefundMoney:0,
    // 用户输入的退款金额
    refundMoney:0,
    imgList:[],
    uploadImg:[],
  }, 
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.setData({
      goodsLogo: app.globalData.confInfo.goodsLogo,
      resourceDomain:app.globalData.resourceDomain,
      orderId:options.orderId
    });
  },
  
  /**
  * 生命周期函数--监听小程序显示
  */
  onShow: function () {
    var that = this;
    that.setData({
      goods: []
    });
    that.getDate();
  },
  refundMoneyChange:function(e){
    let refundMoney = e.detail.value;
    let _reg = /^0(\d{1,})/g;
    if(_reg.test(refundMoney)){
      refundMoney = refundMoney.replace(_reg,(m,p1)=>p1)
    }
    let reg = /0\.\d{0,2}|(^[1-9]\d*$|^[1-9]\d*\.\d{0,2})/ig;
    var rs = refundMoney.match(reg);
    if (null==rs || 0==rs.length) {
      // 未匹配到
      rs = 0;
    }
    this.setData({ refundMoney:rs });
    return rs;

  },
  //数据
  getDate: function () {
    var that = this;
    var tokenId = app.globalData.tokenId;
    var orderId = this.data.orderId;
    http.Post("weapp/orderservices/apply", { tokenId: tokenId, orderId:orderId }, function (res) {
      if (res.status == 1) {
        const { goods, reasons } = res.data;
        const _goods = goods.map(x=>({...x, currNum:1}));
        let _tmp = [];
        for(let i in reasons){
            _tmp.push({value:reasons[i].dataVal, text:reasons[i].dataName})
        }
        const _currOptJson = that.data.optJson;
        _currOptJson['serviceType']['options'] = _tmp;
        that.setData({
          goods:_goods,
          optJson:_currOptJson
        })
      }
      wx.hideLoading();
    });
  },
  // 商品选中事件
  goodsCheck:function({ currentTarget: { dataset: { id, index } } }){
    const _goods = this.data.goods;
    _goods[index].isCheck = !_goods[index].isCheck;
    this.setData({goods:_goods},()=>this.goodsChange());
  
  },
  // step
  changeNum:function({ currentTarget: { dataset: { id, index, type, val } } }){
    const _goods = this.data.goods;
    let _currNum = _goods[index].currNum;
    if(type=='sub'){
      // 递减
      --_currNum;
      if(_currNum<val)_currNum=val;
    }else{
      ++_currNum;
      if(_currNum>val)_currNum=val;
    }
    _goods[index].currNum = _currNum;
    this.setData({goods:_goods},()=>this.goodsChange());
  },
  // 记录用户输入的数量
  inputNum:function({ detail:{ value }, currentTarget: { dataset: { id, index } } }){
    const _goods = this.data.goods;
    const min = 1;
    const max = _goods[index].goodsNum;
    let val = value;
    if(value<1){
      // 小于最小值
      val = min;
    }else if(value>max){
      // 大于最大值
      val = max;
    }
    _goods[index].currNum = val;
    this.setData({goods:_goods},()=>this.goodsChange());
    return {value:val};
  },
  remarkChange:function({detail:{ value }}){
    this.setData({serviceRemark:value})
  },
  showDrawer: function ({ currentTarget: { dataset: { type } } }) {
    this.setData({
      currOpt:type,
      openDrawer:true
    })
  },
  // 用户点击选中
  optClick:function({ currentTarget: { dataset: { value, text, index } } }){
    const { currOpt, optJson } = this.data;
    let _currOptJson = optJson[currOpt];
    const _map = _currOptJson.options.map((x,i)=>{
      x.isCheck=(i==index);
      return x;
    });
    _currOptJson = {
      ..._currOptJson,
      currText:text,
      value,
      options:_map
    }
    optJson[currOpt] = _currOptJson;
    this.setData({optJson});
  },
  doneChose:function(){
    const { currOpt, optJson } = this.data;
    if(currOpt=='serviceType' && optJson[currOpt].value==-1){
      app.prompt('请选择一项');
      return;
    }
    this.setData({openDrawer:false});
  },

  goodsChange: function () {
    if (this.data.goodsServiceType != 2) {
      this._getRefundableMoney();
    }
  },
  // 获取可退款金额
  _getRefundableMoney: function () {
    var that = this;
    const { goods, orderId } = this.data;
    const _gids = []; // 被勾选中的商品id
    const _glist = {}; // {"goodsNum_商品id":"数量"} {goodsNum_59:1}
    goods.map(x => {
      if (x.isCheck) {
        const _key = 'num_' + x.id;
        _gids.push(x.id);
        _glist[_key] = x.currNum || 1;
      }
    })
    _glist.ids = _gids.join(',');
    _glist.orderId = orderId;
    _glist.tokenId = app.globalData.tokenId;
    http.Post("weapp/orderservices/getrefundableMoney", _glist, function (res) {
      if (res.status == 1) {
        that.setData({ canRefundMoney: res.data.totalMoney })
      }
    })

  },

  //上传
  upload:function(){
    var that = this;
    var imgList = that.data.imgList;
    var count = 5;
    count = count - imgList.length;
    if (count==0){
      app.prompt('最多只能上传5张');
      return false;
    }
    wx.chooseImage({
      count: count,
      sizeType: ['original', 'compressed'],
      sourceType: ['album', 'camera'],
      success(res) {
        var tempFilePath = res.tempFilePaths;
        imgList = imgList.concat(tempFilePath);
        that.setData({
          imgList: imgList
        });
        if (imgList.length > 0) {
          that.uploadImg();
        }
      }
    })
  },

  uploadImg: function () {
    var that = this;
    var tokenId = app.globalData.tokenId;
    var imgList = that.data.imgList;
    var uploadImg = [];
    for (var i = 0; i < imgList.length; i++) {
      http.Upload('weapp/users/uploadPic', imgList[i], { tokenId: tokenId, dir: 'appraises', isThumb: 1 }, function (res) {
        if (res.status==1){
          var img = res.savePath+res.name;
          uploadImg = uploadImg.concat(img);
          that.setData({
            uploadImg: uploadImg
          });
        }
      });
    }
  },
  //删除图片
  deleteImg:function(e){
    var that = this;
    var index = e.currentTarget.dataset.index;
    var imgList = that.data.imgList;
    imgList.splice(index, 1);
    that.setData({
      imgList: imgList
    })
  },


  // 提交
  commit:function(){
    const { orderId, optJson, serviceRemark, refundMoney, uploadImg:serviceAnnex, goods } = this.data;
    let postData = {
        orderId,
        serviceType:optJson['serviceType'].value,
        goodsServiceType:optJson['goodsServiceType'].value,
        serviceRemark,
        refundMoney,
        serviceAnnex
    };
    if(postData.serviceType==-1){
        return app.prompt('请选择申请原因');
    }
    const _gids = []; // 被勾选中的商品id
    const _glist = {}; // {"goodsNum_商品id":"数量"} {goodsNum_59:1}
    goods.map(x=>{
        if(x.isCheck){
            const _key = 'goodsNum_' + x.id;
            _gids.push(x.id);
            _glist[_key] = x.currNum || 1;
        }
    })
    if (_gids.length == 0) {
        return app.prompt('请至少勾选一件商品');
    }
    postData = {
        ...postData,
        ..._glist,
        ids:_gids.join(','),
        tokenId:app.globalData.tokenId
    }
    http.Post("weapp/orderservices/commit", postData, function (res) {
      app.prompt(res.msg);
      if(res.status==1){
        wx.navigateBack();
      }
    })
   }
})