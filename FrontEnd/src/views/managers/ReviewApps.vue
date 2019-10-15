<template>
  <div class="animated fadeIn">
    <div>
      <nav class="navbar">
        <a class="navbar-brand">
          <h4>
            <b>待審核APP</b>
          </h4>
        </a>
        <form class="form-inline">
          <input
            class="form-control mr-sm-2"
            type="search"
            placeholder="開發人員"
            aria-label="Search"
            v-model="keyword"
          />
          <!-- <button
            class="btn btn-outline-success my-2 my-sm-0"
            type="submit"
            v-on:click="searchbtn(keyword)"
          >搜尋</button> -->
        </form>
      </nav>
    </div>

    <b-card no-body class="card-default" style="text-align: center">
      <table class="table" style="table-layout:fixed;">
        <thead>
          <tr>
            <th scope="col" width="10%">No.</th>
            <th scope="col">APP名稱</th>
            <th scope="col" width="35%">簡介</th>
            <th scope="col">開發人員</th>
            <th scope="col" width="100px">上傳日期</th>
            <th scope="col" width="100px">是否同意</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(event,index) in search(keyword)" v-bind:key="event.id">
            <td>{{index+1}}</td>
            <td>{{event.appName}}</td>
            <td>{{event.summary}}</td>
            <td>{{event.name}}</td>
            <td>{{event.created_at}}</td>
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
      events: [],
    };
  },
  created() {
    EventService.checkApps()
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
        if (event.name.indexOf(keyword) != -1) {
          newList.push(event);
        }
      });
      return newList;
    },
    CheckOK(id) {
      axios
        .put("http://127.0.0.1:8000/api/Admin/appCheckOk/" + id)
        .then(res => {
          this.events = res.data;  
           console.log(res);        
        })
        .catch(error => {
          console.log(error.res);
        });
    },
    goBack(id) {
      axios
        .put("http://127.0.0.1:8000/api/Admin/appGoBack/" + id)
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

