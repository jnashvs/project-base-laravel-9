<!-- Vue component -->
<template>
  <div class="file-types-container row">
    <form @submit.prevent="onSubmit">
      <div class="form-group">
        <label for="staticEmail" class="col-sm-2 col-form-label">Diretoria</label>
        <div class="col-sm-12">
          <input type="text" class="form-control" v-model="form.directory"/>
        </div>
      </div>

      <div class="form-group">
        <label for="staticEmail" class="col-sm-2 col-form-label">Título</label>
        <div class="col-sm-12">
          <input type="text" class="form-control" v-model="form.title"/>
        </div>
      </div>

      <div class="form-group">
        <label for="staticEmail" class="col-sm-2 col-form-label">Extensão</label>
        <div class="col-sm-12">
          <multiselect
            name="file-type"
            :value="value"
            v-model="form.extensions"
            tag-placeholder="Add this as new tag"
            placeholder="Search or add a tag"
            label="name"
            track-by="code"
            :options="options"
            :multiple="true"
            :taggable="true"
            @tag="addTag"
          ></multiselect>
        </div>
      </div>

      <div class="form-group">
        <label for="staticEmail" class="col-sm-2 col-form-label">Tam. Máximo (Mb)</label>
        <div class="col-sm-12">
          <input type="number" class="form-control" v-model="form.max_file_size"/>
        </div>
      </div>

      <div class="d-flex justify-content-end my-4">
        <a :href="cancelurl" class="btn bg-gradient-dark btn-md mt-4 mb-4 me-2">Cancelar</a>
        <button type="submit" class="btn bg-gradient-dark btn-md mt-4 mb-4">Guardar</button>
      </div>
      
    </form>
  </div>
</template>

<script>
import Multiselect from "vue-multiselect";
export default {
  // OR register locally
  components: { Multiselect },
  props:{
    cancelurl: {
      type: String,
      required: true
    },
    data: {
      //type: Object
    },
  },
  data() {
    return {
      errors: [],
      value: [],
      options: [
        { name: "image/*", code: "img" },
        { name: "image/jpg", code: "jpg" },
        { name: "image/jpeg", code: "jpeg" },
        { name: "image/gif", code: "gif" },
        { name: "image/png", code: "png" },
        { name: "application/doc", code: "doc" },
        { name: "application/docx", code: "docx" },
        { name: "application/pdf", code: "pdf" },
        { name: "application/xls", code: "xls" },
        { name: "application/xlsx", code: "xlsx" },
        { name: "application/csv", code: "csv" },
      ],
      form: {
        id: '',
        directory: '',
        title: '',
        extensions: '',
        max_file_size: '',
      },
      emptyForm: {
        id: '',
        directory: '',
        title: '',
        extensions: '',
        max_file_size: '',
      }
    };
  },
  methods: {
    addTag(newTag) {
      const tag = {
        name: newTag,
        code: newTag.substring(0, 2) + Math.floor(Math.random() * 10000000)
      };
      this.options.push(tag);
      this.value.push(tag);
    },
    onSubmit(){
      console.log(JSON.stringify(this.form));

      let url = "/file-types/create";

      if (this.form.id)
        url = `/file-types/update/${this.form.id}`;

      axios
        .post(url, this.form)
        .then(function(response) {
          console.log(response)
        })
        .catch(e => {
          // if (errors.response.status == 422) {
          //     this.errors = errors.response.data.errors;
          // } else {
          //   console.log(errors.response.status);
          // }
        });

      this.form = this.emptyForm;
    }
  },
  created(){
    if(this.data){
      this.form = JSON.parse(this.data);
    }
  }
};
</script>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>