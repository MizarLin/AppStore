<template>
  <div class="animated fadeIn">
    <nav class="navbar">
      <a class="navbar-brand">
        <h4>
          <b>後台管理</b>
        </h4>
      </a>
    </nav>

    <b-card no-body class="card-default">
      <b-card-body>
        <h1>目前有 {{events.appCount}} 個 APP 尚未審核</h1>
      </b-card-body>
    </b-card>

    <b-card no-body class="card-default">
      <b-card-body>
        <h1>目前有 {{events.devCount}} 位 開發人員 尚未審核</h1>
      </b-card-body>
    </b-card>

    <nav class="navbar">
      <a class="navbar-brand">
        <h4>
          <b>下載前五名APP</b>
        </h4>
      </a>
    </nav>

    <b-card no-body class="card-default" style="text-align: center">
      <!-- <b-card-body> -->
        <table class="table" style="table-layout: fixed;">
          <thead>
            <tr>
              <th scope="col" width="50px">No.</th>
              <th scope="col">APP名稱</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(event,index) in events.top5" v-bind:key="event.id">
              <td>{{index+1}}</td>
              <td>{{event}}</td>
            </tr>
          </tbody>
        </table>
      <!-- </b-card-body> -->
    </b-card>
  </div>
</template>

<script>
import EventService from "@/service/EventService.js";

export default {
  data() {
    return {
      events: []
    };
  },
  created() {
    EventService.homePage()
      .then(response => {
        this.events = response.data;
        // console.log(response);
      })
      .catch(error => {
        console.log("There was an error:", error.response);
      });
  }
};
</script>