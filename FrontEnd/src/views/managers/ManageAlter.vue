<template>
  <div class="animated fadeIn">
    <nav class="navbar">
      <a class="navbar-brand">
        <h4>
          <b>個人資料修改</b>
        </h4>
      </a>
    </nav>

    <b-card no-body class="card-default">
      <b-card-body>
        <form>
          <!-- <div class="form-group row">
            <label for="" class="col-sm-2 col-form-label">我的頭像</label>
            <div class="col-sm-10 input-group">
              <input type="text" class="form-control" placeholder="請選擇圖片" aria-label="Recipient's username" aria-describedby="button-addon2">
              <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="button" id="button-addon2">上傳圖片</button>
              </div>
            </div>
          </div>-->

          <div class="form-group row">
            <label for="inputName" class="col-sm-2 col-form-label">姓名</label>
            <div class="col-sm-10">
              <input
                type="text"
                class="form-control"
                id="inputName"
                placeholder="Name"
                v-model.lazy="input.name"
              />
            </div>
          </div>

          <!-- <div class="form-group row">
            <label
              for="inputPhone"
              class="col-sm-2 col-form-label"
              pattern="09\d{2}\-?\d{3}\-?\d{3}"
            >我的電話</label>
            <div class="col-sm-10">
              <input type="phone" class="form-control" id="inputPhone" placeholder="09xx-xxx-xxx" />
            </div>
          </div>-->

          <div class="form-group row">
            <label for="inputOldPsw" class="col-sm-2 col-form-label" pattern="[a-zA-Z0-9]{8,12}">請輸入密碼</label>
            <div class="col-sm-10">
              <input
                type="password"
                class="form-control"
                id="inputOldPsw"
                placeholder="請輸入密碼"
                v-model.lazy="input.oldPwd"
              />
            </div>
          </div>

          <div class="form-group row">
            <label
              for="inputNewPsw1"
              class="col-sm-2 col-form-label"
              pattern="[a-zA-Z0-9]{8,}"
            >請輸入新的密碼</label>
            <div class="col-sm-10">
              <input
                type="password"
                class="form-control"
                id="inputNewPsw1"
                placeholder="請輸入8-12位數含英文及數字之新密碼"
                v-model.lazy="input.newPwd"
              />
            </div>
          </div>

          <div class="form-group row">
            <label
              for="inputNewPsw2"
              class="col-sm-2 col-form-label"
              pattern="[a-zA-Z0-9]{8,}"
            >請再次確認密碼</label>
            <div class="col-sm-10">
              <input
                type="password"
                class="form-control"
                id="inputNewPsw2"
                placeholder="請再次確認密碼"
                v-model.lazy="input.pwdCheck"
              />
            </div>
          </div>

          <div style="text-align: center">
            <button type="submit" class="btn btn-primary" @click="submitButton">確認送出</button>
          </div>
        </form>
      </b-card-body>
    </b-card>
  </div>
</template>

<script>
import EventService from "@/service/EventService.js";
export default {
  data() {
    return {
      input: {
        name: "",
        oldPwd: "",
        newPwd: "",
        pwdCheck: ""
      }
    };
  },
  methods: {
    submitButton() {
      let { name, oldPwdm, newPwd, pwdCheck } = this.input;
      axios
        .put("http://127.0.0.1:8000/api/Admin/1", this.input)
        .then(res => {
          console.log(res.data);
        })
        .catch(error => {
          console.log(error);
        });
    }
  }

};
</script>