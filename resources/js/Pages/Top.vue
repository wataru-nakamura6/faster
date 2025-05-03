<script setup>
import {ref} from 'vue'
import {router} from '@inertiajs/vue3'
import Header from "@/Components/Header.vue";
import EditSite from "@/Components/EditSite.vue";

const {site_list} = defineProps({
    site_list: Array,
})

const form = ref({
    name: '',
    url: ''
})

//登録、更新処理
const flashMessage = ref('')
const flashMessageType = ref('')
const showToast = ref(false)

const storeSite = () => {
    router.post('/save_site', form.value, {
        onSuccess: () => {
            form.value = {name: '', url: ''}
            router.reload({only: ['site_list']})
            closeEvent('success')
        },
        onError: (errors) => {
            console.error(errors)
            closeEvent('errors')
        }
    })
}

// トーストを表示して3秒後に非表示にする
const triggerToast = () => {
    showToast.value = true
    setTimeout(() => {
        showToast.value = false
    }, 3000)
}

//モーダル処理
const showModal = ref(false)
const selectedSite = ref(null)

const openEditModal = (site) => {
    selectedSite.value = site
    showModal.value = true
}

const closeEvent = (result = false) => {
    showModal.value = false;
    if (result !== false) {
        flashMessage.value = result === 'success' ? '登録が完了しました' : 'エラーが発生しました'
        flashMessageType.value = result || 'errors'
        triggerToast()
    }
}

const selectedId = ref(0)
const selectRow = (id) => {
    selectedId.value = id
}
</script>

<template>
    <Header/>
    <div id="main_wrap">
        <div class="store_area">
            <form @submit.prevent="storeSite">
                <input v-model="form.name" placeholder="サイト名"/>
                <input v-model="form.url" placeholder="URL"/>
                <button type="submit">登録</button>
            </form>
        </div>
        <div class="site_list">
            <h2>登録サイト一覧</h2>
            <table>
                <thead>
                <tr>
                    <th>タイトル</th>
                    <th>URL</th>
                    <th>登録日時</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <template v-for="site in site_list" :key="site.id">
                    <tr @click="selectRow(site.id)">
                        <td>{{ site.name }}</td>
                        <td>{{ site.url }}</td>
                        <td>{{ site.created_at }}</td>
                        <td>
                            <button @click="openEditModal(site)">
                                <img src="/images/pen.svg" alt="編集">
                            </button>
                            <button>
                                <div class="stop_icon"></div>
                            </button>
                        </td>
                    </tr>
                    <tr class="site_tag">
                        <td v-if="selectedId === site.id" colspan="4" class="p-0">
                            <div class="accordion-content">
                                高速化タグ：{{ site.id }}
                            </div>
                        </td>
                    </tr>
                </template>
                </tbody>
            </table>
        </div>
    </div>

    <transition name="toast">
        <div
            v-if="showToast"
            class="toast"
            :class="`${flashMessageType}_message`">
            {{ flashMessage }}
        </div>
    </transition>

    <EditSite
        :site="selectedSite"
        :isOpen="showModal"
        :flashMessage="flashMessage"
        :flashMessageType="flashMessageType"
        @closeEvent="closeEvent"
    />
</template>

<style lang="scss" scoped>
#main_wrap {
    max-width: 1240px;
    margin: auto;
    padding: 40px 80px;
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
    }

    .site_list {
        border: 1px solid #E0E0E0;
        border-radius: 8px;
        margin-top: 30px;
        box-shadow: #eee 0 0 8px;
        padding: 8px 16px;

        h2 {
            padding-bottom: 8px;
            font-weight: bold;
            font-size: 14px;
            line-height: 2;
        }

        table {
            table-layout: auto;
            width: 100%;

            tr {
                &:last-child {
                    border: none;
                }

                th {
                    font-size: 12px;
                    color: #858585;
                    font-weight: bold;
                    line-height: 2;

                    &:first-child {
                        width: 160px;
                    }

                    &:first-child,
                    &:nth-child(2) {
                        text-align: left;
                    }
                }

                td {
                    padding: 8px 0;
                    text-align: left;

                    &:last-child {
                        padding-right: 0;
                        display: flex;
                        align-items: center;
                        justify-content: space-around;
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

                        .stop_icon {
                            height: 10px;
                            width: 10px;
                            background: #fff;
                            border-radius: 2px;
                        }
                    }
                }

                &.site_tag {
                    border-bottom: 1px solid #E0E0E0;

                    td {
                        display: inline-block;
                    }
                }
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
