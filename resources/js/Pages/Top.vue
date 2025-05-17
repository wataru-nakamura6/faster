<script setup>
import {ref} from 'vue'
import SiteList from "@/Components/SiteList.vue";
import EditSite from "@/Components/EditSite.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const {site_list} = defineProps({
    site_list: Array,
})

const showCreateModal = ref(false)

</script>

<template>
    <AuthenticatedLayout>
        <div id="main_wrap">
            <div class="create_modal_wrap">
                <button
                    type="button"
                    class="create_modal_button"
                    @click="showCreateModal=true"
                >
                    <img src="/images/plus.svg" alt="">
                    <span>サイトを追加する</span>
                </button>
            </div>
            <SiteList :site_list="site_list"/>
        </div>
        <EditSite
            :isOpen="showCreateModal"
            @isClose="showCreateModal=false"
        />
    </AuthenticatedLayout>
</template>

<style lang="scss" scoped>
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
        height: 48px;
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

.create_modal_wrap {
    opacity: 0.4;
    margin-bottom: 16px;

    &:hover {
        opacity: 1;
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
