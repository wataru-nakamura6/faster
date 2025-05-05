<script setup>
import {reactive, watch} from 'vue'
import {router} from '@inertiajs/vue3'
import {useToast} from "vue-toast-notification";

const props = defineProps({
    site: Object,
    isOpen: Boolean
})

const emits = defineEmits(['isClose'])

const editableSite = reactive({
    id: null,
    name: '',
    url: ''
})

// 編集対象が変わったら内容を反映
watch(
    () => props.site,
    (newSite) => {
        if (newSite) {
            editableSite.id = newSite.id
            editableSite.name = newSite.name
            editableSite.url = newSite.url
        }
    },
    { immediate: true }
)

let postRoute = '';
const submitRoute = () => {
    // 更新処理
    postRoute = `/site/update/${editableSite.id}`;

    if(!editableSite.id) {
        // 新規登録
        postRoute = `/site/create`;
        delete editableSite.id;
    }

    submitSite();
}

const submitSite = () => {
    router.post(postRoute, editableSite, {
        onSuccess: ({props: {flash}}) => {
            useToast().success(flash?.message)
            router.reload({ only: ['site_list'] })
            emits('isClose');
        },
        onError: (errors) => {
            useToast().error(Object.values(errors).join('\n'))
        }
    })
}
</script>

<template>
    <div v-if="isOpen" class="modal_overlay">
        <div class="modal_content">
            <h2>サイト情報を編集</h2>
            <input class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                   v-model="editableSite.name"
                   placeholder="サイト名" />
            <input class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                   v-model="editableSite.url"
                   placeholder="URL" />
            <div class="actions">
                <button @click="submitRoute">保存</button>
                <button @click="$emit('isClose')">キャンセル</button>
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
.modal_overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.4);
    display: flex;
    justify-content: center;
    align-items: center;

    .modal_content {
        background: white;
        padding: 30px;
        border-radius: 8px;
        width: 500px;

        h2{
            padding: 15px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        input{
            width: 100%;
            margin-bottom: 4px;
        }

        .actions {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;

            button{
                background: #0f0f0f;
                width: 100px;
                padding: 10px;
                color: #fff;
                border-radius: 50px;
            }
        }
    }
}
</style>
