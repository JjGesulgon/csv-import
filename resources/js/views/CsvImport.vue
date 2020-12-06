<template>
  <div class="card">
      <div class="card-body">
        <div class="d-none d-sm-block display-4 font-weight-lighter text-left">Data Mapping</div>
        <hr>
        <div class="form-row mt-3">
          <div class="col-md-12">
            <label for="input-file-import" class="font-weight-bold">File</label>
            <input type="file" class="form-control-file" :class="{ ' is-invalid' : error.message }" id="input-file-import" name="file_import" ref="import_file"  @change="onFileChange">
            <div v-if="error.message" class="invalid-feedback">
              {{ error.message }}
            </div>
            <button class="btn btn-primary btn-sm mt-3" @click="proceedAction"> Analyze </button>

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
        }
      },
      methods: {
         onFileChange(e) {
        this.import_file = e.target.files[0];
    },

    proceedAction() {
        let formData = new FormData();
        formData.append('import_file', this.import_file);

          axios.post('/api/contact/read', formData, {
              headers: { 'content-type': 'multipart/form-data' }
            })
            .then(response => {
                if(response.status === 200) {
                  // codes here after the file is upload successfully
                }
            })
            .catch(error => {
                // code here when an upload is not valid
                this.error = error.response.data
                console.log('check error: ', this.error)
            });
        },
      }
  }
</script>