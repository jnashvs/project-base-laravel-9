<template>
  <div class="row main-file-container">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header">
          <div class="row">
            <div class="col-6 d-flex align-items-center">
              <a class="btn bg-gradient-dark me-3 form-control my-auto" href="#" @click="fileUpload.showPanel = true"><i
                  class="fas fa-plus"></i> Add Files</a>
              <select v-model="fileTypes.selected" class="form-select form-control" aria-label="Default select example"
                @change="findFileType">
                <option value="" selected>Select the directory</option>
                <option v-for="(item, index) in fileTypes.all" :key="index" :value="item.id">{{ item.title }}</option>
              </select>
            </div>
            <div class="col-6 text-end">
              <div class="form-group d-flex justify-content-end my-auto">
                <div class="me-3">
                  <input class="form-control" v-model="searchValue" @focusout="getFiles()" type="text"
                    placeholder="Search file here" name="search_files">
                </div>
              </div>
            </div>
          </div>
          <div class="row container-file-upload my-4" v-if="fileUpload.showPanel && !loading">
            <dropzone-area @updateFiles="getFiles" :fileType="fileTypes.selectedFileType" />
          </div>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
          <EasyDataTable show-index buttons-pagination :headers="headers" :items="items" :loading="loading">
            <template #loading>
              <img src="https://thumbs.gfycat.com/AngelicYellowIberianmole.webp" style="width: 60px; height: 100px" />
            </template>
            <template #item-path="{ path }">
              <div class="player-wrapper">
                <img class="avator" :src="path" alt="" />
              </div>
            </template>
            <template #item-url="{ path }">
              <div class="player-url">
                <a :href="path" target="_blank">{{ path }}</a>
              </div>
            </template>
            <template #item-date="{ created_at }">
              {{ created_at }}
            </template>
            <template #item-id="{ id }">
              <button class="btn btn-danger btn-xs m-auto py-1 px-2" @click="deleteFile(id)">
                <i class="fa fa-times"></i>
              </button>
            </template>
          </EasyDataTable>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import DropzoneArea from './DropzoneArea.vue';
export default {
  components: {
    DropzoneArea: DropzoneArea,
  },
  data() {
    return {
      loading: true,
      url: "/files/all",
      fileTypes: {
        all: [],
        selected: '',
        selectedFileType: {}
      },
      searchValue: '',
      fileUpload: {
        showPanel: false,
      },
      headers: [
        { text: "PATH", value: "path" },
        { text: "URL", value: "url", sortable: true },
        { text: "DATE", value: "date", sortable: true },
        { text: "ACTION", value: "id" }
      ],
      items: []
    };
  },
  methods: {
    getFileTypes() {
      axios
        .get('/file-types/all')
        .then(response => {
          this.fileTypes.all = response.data;
        })
        .catch(errors => {
          console.log(errors);
        });
    },
    getFiles() {
      axios
        .get(`${this.url}?search=${this.searchValue}&directory=${this.fileTypes.selected}`)
        .then(response => {
          this.loading = false;
          this.items = response.data;
        })
        .catch(errors => {
          console.log(errors);
        });
    },
    findFileType() {
      this.getFiles();
      this.fileTypes.selectedFileType = this.findFileTypesByValue(this.fileTypes.selected);
    },
    deleteFile(id) {
      if (!id) return;
      axios
        .delete(`/files/remove/${id}`)
        .then(response => {
          this.getFiles();
        })
        .catch(errors => {
          console.log(errors);
        });
    },
    findFileTypesByValue(value) {
      return this.fileTypes.all.find(fileType => fileType.id === value);
    }
  },
  created() {
    this.getFiles();
    this.getFileTypes();
  }
};
</script>

<style>
.operation-wrapper .operation-icon {
  width: 20px;
  cursor: pointer;
}

.player-wrapper {
  padding: 5px;
  display: flex;
  align-items: center;
  justify-items: center;
}

.avator {
  margin-right: 10px;
  display: inline-block;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  object-fit: cover;
  box-shadow: inset 0 2px 4px 0 rgb(0 0 0 / 10%);
}
</style>
