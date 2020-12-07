<template>
  <div class="card">
      <div class="card-body">
        <h1 class="d-none d-sm-block font-weight-lighter text-left">Data Mapping</h1>
        <hr>
        <div class="form-row mt-3">
          <div class="col-md-12">
            <label for="input-file-import" class="font-weight-bold">File 
              <span>
                <input type="file" class="form-control-file" :class="{ ' is-invalid' : error.message }" id="input-file-import" name="file_import" ref="import_file"  @change="onFileChange">
              </span>
            </label>
            <div v-if="error.message" class="invalid-feedback">
              {{ error.message }}
            </div>
            <button class="btn btn-primary btn-sm" @click="proceedAction"> Analyze file </button>
            <div class="progress mt-3" height="30px;" v-if="!dataHasLoaded">
                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;"></div>
            </div>
            <div v-else>
              <div class="mt-3" v-if="number_of_rows">
                No. of Items: {{ number_of_rows }} 
              </div>
              <table class="table table-hover table-sm" v-if="table_data.length != 0">
                <thead>
                    <tr>
                      <!-- <th scope="col" class="ideal-font" v-for="header in table_headers" :key="header.id">{{ header }}</th> -->
                      <th scope="col" class="ideal-font">Team ID</th>
                      <th scope="col" class="ideal-font">Name</th>
                      <th scope="col" class="ideal-font">Phone</th>
                      <th scope="col" class="ideal-font">Email</th>
                      <th scope="col" class="ideal-font">Sticky Phone Number ID</th>
                    </tr>
                </thead>
                <tbody>
                    <tr :id="item.id" v-for="item in table_data" :key="item.team_id">
                      <td class="ideal-font">{{ item[Object.keys(item)[0]] }}</td>
                      <td class="ideal-font">{{ item[Object.keys(item)[1]] }}</td>
                      <td class="ideal-font">{{ item[Object.keys(item)[2]] }}</td>
                      <td class="ideal-font">{{ item[Object.keys(item)[3]] }}</td>
                      <td class="ideal-font">{{ item[Object.keys(item)[4]] }}</td>
                    </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
</template>
<script>
  export default {
     data() {
        return {
          error: {},
          import_file: '',
          number_of_rows: '',
          table_headers: [],
          table_data: [],
          dataHasLoaded: true,
        }
      },
      methods: {
         onFileChange(e) {
        this.import_file = e.target.files[0];
    },

    proceedAction() {
        this.dataHasLoaded = false;
        let formData = new FormData();
        formData.append('import_file', this.import_file);

          axios.post('/api/contact/read', formData, {
              headers: { 'content-type': 'multipart/form-data' }
            })
            .then(res => {
                this.number_of_rows = res.data.numberOfRows;
                this.table_headers = res.data.headers;
                this.table_data = res.data.dataFromFile;

                this.dataHasLoaded = true;
            })
            .catch(error => {
                // code here when an upload is not valid
                this.error = error.response.data
                console.log('check error: ', this.error)
                this.dataHasLoaded = true;
            });
        },
      }
  }
</script>