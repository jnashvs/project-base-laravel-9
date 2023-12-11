<template>
    <div v-if="fileType.title">
        <div v-bind="getRootProps()">
            <input v-bind="getInputProps()" />
            <div>
                <p>Drag 'n' drop some files here, or click to select files</p>
            </div>

            <div id="dropzone" class="vue-dropzone dropzone dz-clickable">
                <div class="dz-default dz-message">
                    <span>
                        <p class="my-0" v-if="fileType.max_file_size"><b>Tamanho máximo:</b> {{ fileType.max_file_size }} Mb
                        </p>
                        <p class="my-0" v-if="fileExtensions"><b>Extensões:</b> {{ fileExtensions }} </p>
                        <p class="my-0" v-if="fileType.directory"><b>Directório:</b> {{ fileType.directory }}</p>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div v-else>
        <p>
            <b>Please choose the target directory!</b>
        </p>
    </div>
</template>
  
<script>
import { useDropzone } from "vue3-dropzone";
import { ref } from 'vue';
export default {
    name: "dropzone-area",
    props: {
        fileType: {
            type: Object,
            required: false,
        },
    },
    emit: ['updateFiles'],
    setup(props, { emit }) {
        const fileExtensions = ref('');

        const saveFiles = (files) => {
            const formData = new FormData();
            for (var x = 0; x < files.length; x++) {
                formData.append("file", files[x]);
            }
            axios
                .post(`/files/upload?path=${props.fileType.directory}`, formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                })
                .then((response) => {
                    emit('updateFiles');
                })
                .catch((err) => {
                    console.error(err);
                });
        };

        const filterFileType = () => {
            if (props.fileType && props.fileType.extensions) {
                const list_extensions = JSON.parse(props.fileType.extensions);

                fileExtensions.value = list_extensions.map(element => element.name).join(',');
            }
        }

        function onDrop(acceptFiles, rejectReasons) {
            saveFiles(acceptFiles); // saveFiles as callback
            console.log(rejectReasons);
        }

        const { getRootProps, getInputProps, ...rest } = useDropzone({
            onDrop,
            maxSize: props.fileType.max_file_size * 1024 * 1024, // convert max_file_size to bytes
            acceptedFiles: fileExtensions.value.split(',').map(ext => `.${ext}`),
        });


        filterFileType();

        return {
            fileExtensions,
            getRootProps,
            getInputProps,
            ...rest,
        };
    },
};
</script>