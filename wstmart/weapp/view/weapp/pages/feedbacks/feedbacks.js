var app = getApp();
var http = require("../../utils/request.js");
Page({
  data: {
    modelStatus: false,
    allStatus: true,
    feedbackTypes: '',
    selectId: 0,
    domain: app.globalData.domain,
    feedbackType: '',
    feedbackContent: '',
    contact:'',
    feedbackTypeText: '请选择反馈问题类型'
  },
  onLoad: function (e) {
    this.setData({
      resourceDomain: app.globalData.resourceDomain,
    })
    this.getFeedbackTypes();
  },


  onReady: function () {

  },
  getFeedbackTypes: function () {
    var that = this;
    http.Post('weapp/feedbacks/getFeedbackTypes', { tokenId: app.globalData.tokenId }, function (res) {
      if (res.status == 1) {
        that.setData({
          feedbackTypes: res.data
        })
      }
    })
  },
  submitInfo: function () {
    var that = this;
    http.Post('weapp', {}, function (res) {
    })
  },
  getText: function (e) {
    this.setData({
      feedbackContent: e.detail.value
    })
  },
  changeContact: function (e) {
    this.setData({
      contact: e.detail.value
    })
  },

  //提交
  submit: function (e) {
    var that = this;
    var tokenId = app.globalData.tokenId;
    var feedbackType = that.data.feedbackType;
    var feedbackContent = that.data.feedbackContent;
    var contact = that.data.contact;
    if (feedbackType == '') {
      app.prompt('请选择反馈问题类型', 'none');
      return false;
    }
    if (feedbackContent == '') {
      app.prompt('反馈内容不能为空', 'none');
      return false;
    }
    if (contact == '') {
      app.prompt('请填写联系方式', 'none');
      return false;
    }
    var data = { tokenId: tokenId, contactInfo:contact,feedbackContent: feedbackContent, feedbackType: feedbackType}
    http.Post("weapp/feedbacks/add", data, function (res) {
      if (res.status == 1) {
        wx.showToast({
          title: res.msg,
          icon: 'success',
          duration: 2000,
          success: function () {
            setTimeout(function () {
              wx.navigateBack({
                delta: 1
              })
            }, 800)
          }
        })
      } else {
        app.prompt(res.msg, '');
      }
    });
  },

  powerDrawer: function (e) {
    var currentStatu = e.currentTarget.dataset.statu;
    this.util(currentStatu)
  },
  util: function (currentStatu) {
    var animation = wx.createAnimation({
      duration: 300,
      timingFunction: "linear",
      delay: 0,
      transformOrigin: "100% 50% 0"
    });
    this.animation = animation;
    animation.opacity(0.5).translateY(400).step();
    this.setData({
      animationData: animation.export()
    })
    setTimeout(function () {
      animation.opacity(1).translateY(0).step();
      this.setData({
        animationData: animation.export()
      })

      //关闭抽屉  
      if (currentStatu == "close") {
        this.setData(
          {
            modelStatus: false,
            allStatus: true
          }
        );
      }
    }.bind(this), 200)
    // 显示抽屉  
    if (currentStatu == "open") {
      this.setData(
        {
          modelStatus: true,
          allStatus: false
        }
      );
    }
  },
  selectId: function (e) {
    var selectId = e.currentTarget.dataset.id;
    var feedbackTypes = this.data.feedbackTypes;
    var feedbackTypeArr = [];
    Object.keys(feedbackTypes).forEach(function (key) {
      feedbackTypeArr.push(feedbackTypes[key])
    });
    var feedbackTypeText = this.data.feedbackTypeText;

    for (let i = 0; i < feedbackTypeArr.length; i++) {
      if (selectId == feedbackTypeArr[i].dataVal) {
        feedbackTypeText = feedbackTypeArr[i].dataName
      }
    }
    this.setData({
      selectId: selectId,
      feedbackType: selectId,
      feedbackTypeText: feedbackTypeText
    })

  },
  selectIded: function () {
    this.setData({
      feedbackType: this.data.selectId
    })
  }
})