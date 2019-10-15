<template>
  <div class="animated fadeIn">
    <nav class="navbar">
      <a class="navbar-brand">
        <h4>
          <b>類別管理</b>
        </h4>
      </a>
    </nav>

    <div class="input-group mb-3">
      <input type="text" class="form-control" placeholder="新增類別名稱" aria-describedby="classBtn" v-model="className">
      <div class="input-group-append">
        <button class="btn btn-outline-secondary" type="button" id="classBtn" @click="newClass">新增</button>
      </div>
    </div>

    <b-card no-body class="card-default" style="text-align: center">
      <table class="table" style="table-layout: fixed;">
        <thead>
          <tr>
            <th scope="col" width="10%">No.</th>
            <th scope="col">類別名稱</th>
            <th scope="col">此類APP總數</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(event,index) in events" v-bind:key="event.id">
            <td>{{index+1}}</td>
            <td>{{event.name}}</td>
            <td>{{event.level}}</td>
          </tr>
        </tbody>
      </table>
    </b-card>
  </div>
</template>
<script>
import EventService from "@/service/EventService.js";

export default {
  data() {
    return {
      className: "",
      events: []
    };
  },
  created() {
    EventService.memberManage()
      .then(response => {
        this.events = response.data;
      })
      .catch(error => {
        console.log("There was an error:", error.response);
      });
  },
  methods: {
    newClass() {
      axios
        .post("http://127.0.0.1:8000/api/Admin/category/")
        .then(res => {
          this.events = res.data;
        })
        .catch(error => {
          console.log(error.res);
        });
    },
  }
};
</script>