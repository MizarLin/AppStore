<template>
  <div class="animated fadeIn">
    <div>
      <nav class="navbar">
        <a class="navbar-brand">
          <h4>
            <b>APP管理</b>
          </h4>
        </a>
        <form class="form-inline">
          <input
            class="form-control mr-sm-2"
            type="search"
            placeholder="APP名稱"
            aria-label="Search"
            v-model="keyword"
          />
          <!-- <button class="btn btn-outline-success my-2 my-sm-0" type="submit">搜尋</button> -->
        </form>
      </nav>
    </div>

    <b-card no-body class="card-default" style="text-align: center">
      <table class="table" style="table-layout: fixed;">
        <thead>
          <tr>
            <th scope="col" width="10%">No.</th>
            <th scope="col">APP名稱</th>
            <th scope="col" width="55%">簡介</th>
            <th scope="col">系統</th>
            <th scope="col">停用</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(event,index) in search(keyword)" v-bind:key="event.id">
            <td>{{index+1}}</td>
            <td>{{event.appName}}</td>
            <td>{{event.summary}}</td>
            <td>{{event.device}}</td>
            <td>
              <button
                type="button"
                class="btn btn-primary btn-sm"
                @click="stopApp(event.id)"
                v-if="event.stopRight===1"
              >停用</button>
              <!-- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; -->
              <button
                type="button"
                class="btn btn-danger btn-sm"
                @click="restoreApp(event.id)"
                v-if="event.stopRight===0"
              >恢復</button>
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
    EventService.appManage()
      .then(response => {
        this.events = response.data;
      })
      .catch(error => {
        console.log("There was an error:", error.response);
      });
  },
  methods: {
    search(keyword) {
      var newList = [];
      this.events.forEach(event => {
        if (event.appName.indexOf(keyword) != -1) {
          newList.push(event);
        }
      });
      return newList;
    },
    stopApp(id) {
      axios
        .put("http://127.0.0.1:8000/api/Admin/stopApp/" + id)
        .then(res => {
          this.events = res.data;
        })
        .catch(error => {
          console.log(error.res);
        });
    },
    restoreApp(id) {
      axios
        .put("http://127.0.0.1:8000/api/Admin/restoreApp/" + id)
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