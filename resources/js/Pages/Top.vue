<script setup>
import {ref} from 'vue'
import {router} from '@inertiajs/vue3'
import Header from "@/Components/Header.vue";
import SiteList from "@/Components/SiteList.vue";
import {useToast} from "vue-toast-notification";
import EditSite from "@/Components/EditSite.vue";

const {site_list} = defineProps({
    site_list: Array,
})

// TODO: 新規登録処理をコンポーネント&モーダル化
const storeSite = () => {
    router.post('/site/create', form.value, {
        onSuccess: ({props: {flash}}) => {
            useToast().success(flash?.message)
            form.value = {name: '', url: ''}
            router.reload({only: ['site_list']})
        },
        onError: (errors) => {
            // 複数エラーを表示できるようにする
            useToast().error(Object.values(errors).join('\n'))
            console.error(errors)
        }
    })
}

const form = ref({
    name: '',
    url: ''
})

const showCreateModal = ref(false)

</script>

<template>
    <Header/>
    <div id="main_wrap">
            <button
                type="button"
                class="create_modal_button"
                @click="showCreateModal=true"
            >
                <img src="/images/plus.svg" alt="">
                <span>サイトを追加する</span>
            </button>
        <SiteList :site_list="site_list" />
    </div>
    <EditSite
        :isOpen="showCreateModal"
        @isClose="showCreateModal=false"
    />
</template>

<style lang="scss" scoped>
#main_wrap {
    max-width: 1240px;
    margin: auto;
    padding: 24px 80px;
    font-size: 13px;

    input {
        border: 1px solid #E0E0E0;
        border-radius: 50px;
    }

    button {
        background: #0f0f0f;
        color: #fff;
        border-radius: 50px;
        height: 24px;
        width: 24px;
        display: flex;
        align-items: center;
        justify-content: center;

        img {
            width: 14px;
            height: 14px;
            object-fit: contain;
        }

        &.create_modal_button {
            width: 100%;
            padding: 24px 0;
            color: #0f0f0f;
            background: none;
            font-size: 14px;
            line-height: 14px;
            border: 1px dashed #0f0f0f;
            border-radius: 8px;

            img {
                width: 14px;
                height: 14px;
                object-fit: contain;
                margin-bottom: 2px;
                margin-right: 12px;
            }
        }
    }
}

.toast {
    position: fixed;
    bottom: 30px;
    right: 30px;
    min-width: 250px;
    padding: 15px 20px;
    border-radius: 8px;
    font-weight: bold;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    z-index: 9999;
    background: #fff;
}

.success_message {
    background-color: #d4edda;
    color: #155724;
}

.error_message {
    background-color: #f8d7da;
    color: #721c24;
}

.toast-enter-from {
    transform: translateX(100%);
    opacity: 0;
}

.toast-enter-active {
    transition: all 0.5s ease;
}

.toast-enter-to {
    transform: translateX(0);
    opacity: 1;
}

.toast-leave-from {
    transform: translateX(0);
    opacity: 1;
}

.toast-leave-active {
    transition: all 0.5s ease;
}

.toast-leave-to {
    transform: translateX(100%);
    opacity: 0;
}
</style>
