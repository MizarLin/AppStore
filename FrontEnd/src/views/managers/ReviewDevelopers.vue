<template>
  <div class="animated fadeIn">
    <div>
      <nav class="navbar">
        <a class="navbar-brand">
          <h4>
            <b>待審核開發人員</b>
          </h4>
        </a>
        <form class="form-inline">
          <input
            class="form-control mr-sm-2"
            type="search"
            placeholder="申請會員"
            aria-label="Search"
            v-model="keyword"
          />
          <!-- <button class="btn btn-outline-success my-2 my-sm-0" type="submit">搜尋</button> -->
        </form>
      </nav>
    </div>

    <b-card no-body class="card-default" style="text-align: center">
      <table class="table">
        <thead>
          <tr>
            <th scope="col" width="10%">No.</th>
            <th scope="col">申請會員</th>
            <th scope="col">申請日期</th>
            <th scope="col">是否同意</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(event,index) in search(keyword)" :key="event.id">
            <td>{{index+1}}</td>
            <td>{{event.name}}</td>
            <td>{{event.updated_at}}</td>
            <td>
              <button type="button" class="btn btn-primary btn-sm" @click="CheckOK(event.id)">是</button>&nbsp;&nbsp;&nbsp;
              <button
                type="button"
                class="btn btn-danger btn-sm"
                @click="goBack(event.id)"
              >否</button>
            </td>
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
      keyword: "",
      events: []
    };
  },
  created() {
    EventService.checkDev()
      .then(response => {
        this.events = response.data;
        // console.log("data---", response.data);
      })
      .catch(error => {
        console.log("There was an error:", error.response);
      });
  },
  methods: {
    search(keyword) {
      var newList = [];
      this.events.forEach(event => {
        if (event.name.indexOf(keyword) != -1) {
          newList.push(event);
        }
      });
      return newList;
    },
    CheckOK(id) {
      axios
        .put("http://127.0.0.1:8000/api/Admin/devCheckOk/" + id)
        .then(res => {
          this.events = res.data;
        })
        .catch(error => {
          console.log(error.res);
        });
    },
    goBack(id) {
      axios
        .put("http://127.0.0.1:8000/api/Admin/devGoBack/" + id)
        .then(res => {
          this.events = res.data;
        })
        .catch(error => {
          console.log(error.res);
        });
    }
  }
};
</script>