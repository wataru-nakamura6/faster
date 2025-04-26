<script setup>
import {reactive, ref, watch} from 'vue'
import {usePage, router} from '@inertiajs/vue3'

const page = usePage()

const props = defineProps({
    site: Object,
    isOpen: Boolean
})

const emits = defineEmits(['close'])

const editableSite = reactive({
    id: null,
    name: '',
    url: ''
})

const flashMessage = ref('')
const flashMessageType = ref('')
const showToast = ref(false)

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

const submitEdit = () => {
    router.put(`/save_site/${editableSite.id}`, editableSite, {
        onSuccess: () => {
            emits('close')
            router.reload({ only: ['site_list'] })
            flashMessage.value = page.props.flash?.message || ''
            flashMessageType.value = page.props.flash?.message_type || ''
            triggerToast()
        },
        onError: (errors) => {
            console.error(errors)
            flashMessage.value = page.props.flash?.message || 'エラーが発生しました'
            flashMessageType.value = page.props.flash?.message_type || 'error'
            triggerToast()
        }
    })
}

const triggerToast = () => {
    showToast.value = true
    setTimeout(() => {
        showToast.value = false
    }, 3000)
}
</script>

<template>
    <div v-if="isOpen" class="modal_overlay">
        <div class="modal_content">
            <h2>サイト情報を編集</h2>
            <input v-model="editableSite.name" placeholder="サイト名" />
            <input v-model="editableSite.url" placeholder="URL" />
            <div class="actions">
                <button @click="submitEdit">保存</button>
                <button @click="$emit('close')">キャンセル</button>
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
            border: 1px solid #E0E0E0;
            border-radius: 50px;
            margin-bottom: 5px;
            width: 100%;
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
