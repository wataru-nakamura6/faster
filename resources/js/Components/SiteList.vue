<script setup>
import {ref} from 'vue'
import EditSite from "@/Components/EditSite.vue";

const {site_list} = defineProps({
    site_list: {
        type: Object,
        required: true
    },
})

//モーダル処理
const showModal = ref(false)
const selectedSite = ref(null)

const openEditModal = (site) => {
    selectedSite.value = site
    showModal.value = true
}

const selectedId = ref(0)
const selectRow = (id) => {
    selectedId.value = id
}

// アコーディオンアニメーション
const beforeEnter = (el) => {
    el.style.height = '0'
    el.style.opacity = '0'
}

const enter = (el, done) => {
    el.style.transition = 'all 0.3s ease'
    el.style.height = el.scrollHeight + 'px'
    el.style.opacity = '1'
    setTimeout(() => {
        el.style.height = 'auto' // 自然な高さに戻す
        done()
    }, 300)
}

const leave = (el, done) => {
    el.style.transition = 'all 0 ease'
    setTimeout(done)
}
</script>

<template>
    <div class="site_wrap">
        <h2>登録サイト一覧</h2>
        <ul class="site_list">
            <li class="site_item site_title">
                <div class="site_row">
                    <span class="site_cell title">サイト名</span>
                    <span class="site_cell url">URL</span>
                    <span class="site_cell date">登録日時</span>
                    <div class="site_cell actions">操作</div>
                </div>
            </li>
            <li v-for="site in site_list" :key="site.id" class="site_item">
                <div class="site_row" @click="selectRow(site.id)">
                    <span class="site_cell title">{{ site.name }}</span>
                    <span class="site_cell url">{{ site.url }}</span>
                    <span class="site_cell date">{{ site.created_at }}</span>
                    <div class="site_cell actions">
                        <button @click.stop="openEditModal(site)">
                            <img src="/images/pen.svg" alt="編集"/>
                        </button>
                        <button>
                            <div class="stop_icon"></div>
                        </button>
                    </div>
                </div>

                <transition
                    @before-enter="beforeEnter"
                    @enter="enter"
                    @leave="leave"
                >
                    <div class="accordion" v-show="selectedId===site.id">
                        高速化タグ：dummysite.com?pram={{ site.uuid }}
                    </div>
                </transition>
            </li>
        </ul>
    </div>

    <EditSite
        :site="selectedSite"
        :isOpen="showModal"
        @isClose="showModal=false"
    />
</template>

<style lang="scss" scoped>
.site_wrap {
    border: 1px solid #E0E0E0;
    border-radius: 8px;
    box-shadow: #eee 0 0 8px;
    padding: 8px 16px 16px;

    h2 {
        font-weight: bold;
        font-size: 14px;
        line-height: 2;
    }

    .site_list {
        width: 100%;
        display: flex;
        flex-direction: column;

        .site_item {
            border-bottom: 1px solid #e0e0e0;

            &.site_title {
                color: #999;
                font-size: 12px;
                font-weight: bold;
            }

            .site_row {
                display: flex;
                align-items: center;
                line-height: 2;
                padding: 8px 0;
                cursor: pointer;

                .site_cell {
                    font-size: 12px;
                    color: #0f0f0f;
                    text-align: left;

                    &.title {
                        width: 160px;
                    }

                    &.url {
                        flex: 1;
                    }

                    &.title,
                    &.url {
                        text-align: left;
                        padding-left: 0;
                        text-wrap: nowrap;
                        overflow-x: auto;
                    }

                    &.date {
                        width: 120px;
                        text-align: center;
                    }

                    &.actions {
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        padding-right: 0;
                        width: 64px;

                        button {
                            background: #0f0f0f;
                            color: #fff;
                            border-radius: 50px;
                            height: 24px;
                            width: 24px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            border: none;
                            padding: 0;
                            cursor: pointer;
                            margin-right: 4px;

                            &:last-child {
                                margin-right: 0;
                            }

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
                }
            }

            .accordion {
                font-size: 12px;
                line-height: 2;
                padding: 0 0 8px 0;
            }
        }
    }

}

/* スクロールバー全体 */
::-webkit-scrollbar {
    height: 2px; /* 横スクロール用 */
}

/* スクロールバーのトラック（背景） */
::-webkit-scrollbar-track {
    background-color: #f1f1f1;
    border-radius: 2px;
}

/* スクロールバーのつまみ */
::-webkit-scrollbar-thumb {
    background-color: #0f0f0f;
    border-radius: 2px;
}
</style>
