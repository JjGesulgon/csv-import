<template>
  <div class="card">
      <div class="card-body">
        <div class="container">
          <div class="d-none d-sm-block display-4 px-3 font-weight-lighter text-center">File Upload</div>
        </div>
        <div class="row mt-3">
          <div class="col-md-3">
            <div class="form-group">
              <label for="image" class="ideal-font font-weight-bold">File</label>
              <input id="image" type="file" class="form-control-file" @change="onFileSelected" />
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
      file: null,
    };
  },
  mounted() {
    //
  },
  methods: {
    onFileSelected(event) {
      this.file = event.target.files[0];
    },
  },
  computed: {
    // Add ES6 methods here that needs caching
  },
};
</script>