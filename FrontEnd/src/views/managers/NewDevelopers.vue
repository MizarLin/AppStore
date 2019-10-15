<template>
  <div class="animated fadeIn">
    <nav class="navbar">
      <a class="navbar-brand">
        <h4>
          <b>開發人員資料</b>
        </h4>
      </a>
    </nav>

    <b-card no-body class="card-default">
      <b-card-body>
        <form>
          <div class="form-group row">
            <label for="inputName" class="col-sm-2 col-form-label">名字</label>
            <div class="col-sm-10">
              <input
                type="text"
                class="form-control"
                id="inputName"
                placeholder="Name"
                required
                v-model="input.name"
              />
            </div>
          </div>

          <div class="form-group row">
            <label for="inputUser" class="col-sm-2 col-form-label">身分證字號</label>
            <div class="col-sm-10">
              <input
                type="text"
                class="form-control"
                id="inputUser"
                placeholder="請輸入身分證"
                pattern="^[A-Z]\d{9}$"
                required
                v-model="input.idNumber"
              />
            </div>
          </div>

          <div class="form-group row">
            <label
              for="inputPhone"
              class="col-sm-2 col-form-label"
            >電話</label>
            <div class="col-sm-10">
              <input
                type="text"
                class="form-control"
                id="inputPhone"
                placeholder="09xx-xxx-xxx"
                pattern="^09\d{2}\d{3}\d{3}$"
                required
                v-model="input.phone"
              />
            </div>
          </div>

          <div class="form-group row">
            <label for="inputEmail" class="col-sm-2 col-form-label">E-mail</label>
            <div class="col-sm-10">
              <input
                type="email"
                class="form-control"
                id="inputEmail"
                placeholder="xxx@gmail.com"
                pattern="^.*@gmail\.com$"
                required
                v-model="input.email"
              />
            </div>
          </div>

          <div class="form-group row">
            <label for="inputPsw" class="col-sm-2 col-form-label">密碼</label>
            <div class="col-sm-10">
              <input
                type="password"
                class="form-control"
                id="inputPsw"
                placeholder="至少8-12位英數的密碼限制"
                pattern="[a-zA-Z0-9]{8,12}"
                required
                v-model="input.password"
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
        phone: "",
        email: "",
        idNumber: "",
        password: ""
      }
    };
  },
  methods: {
    submitButton() {
      let { name, phone, email, idNumber, password } = this.input;
      axios
        .post("http://127.0.0.1:8000/api/Admin/newDeveloper", this.input)
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