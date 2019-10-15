<template>
  <div class="wrapper">
    <div class="animated fadeIn">
      <b-row>
        <b-col md="12">
          <b-card header-tag="header" footer-tag="footer">
            <div slot="header">
              <!-- <i class="fa fa-align-justify"></i> -->
              <strong>我的 Apps 下載排行</strong>
              <!-- <small>custom content</small> -->
            </div>
            <b-list-group v-for="event in events" v-bind:key="event.id">
              <b-list-group-item href="#" class="flex-column align-items-start">
                <b-media left-align vertical-align="center">
                  <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">{{event.appName}}</h5>
                    <small class="text-muted">下載次數 :{{event.downloadTimes}}</small>
                  </div>
                  <template v-slot:aside>
                    <b-img
                      v-bind:src="event.appIcon"
                      class="img-circle"
                      width="120"
                      alt="placeholder"
                    ></b-img>
                  </template>
                  <p class="mb-0">{{event.summary}}</p>
                  <!-- <small class="text-muted">Donec id elit non mi porta.</small> -->
                </b-media>
              </b-list-group-item>
            </b-list-group>
          </b-card>
        </b-col>
      </b-row>
    </div>
  </div>
</template>

<script>
export default {
  // name: "list-groups"
  data() {
    return {
      events: []
    };
  },
  created() {
    this.axios
      .get("http://127.0.0.1:8000/api/develop/appRank/2")
      .then(response => {
        this.events = response.data;
      })
      .catch(error => {
        console.log("There was an error:", error.response);
      });
  }
};
</script>

