<script setup>
import { ref } from 'vue'
import axios from 'axios'
import Header from "@/Components/Header.vue";

const { site_list } = defineProps({
    site_list: Array,
})

const file = ref(null)
const result = ref(null)

const saveURL = (e) => {
    file.value = e.target.files[0]
}

const uploadURL = async () => {
    if (!file.value) return

    const formData = new FormData()
    formData.append('file', file.value)

    try {
        await axios.post('/api/upload/image', formData)
        result.value ='アップロード成功'
    } catch (e) {
        result.value = 'アップロード失敗'
    }
}
</script>

<template>
    <Header />
    <div id="main_wrap">
        <div class="register_area">
            <input type="text" @change="saveURL" />
            <button @click="uploadURL">登録</button>
            <p v-if="result">{{ result }}</p>
        </div>
        <div class="site_list">
            <h2>登録サイト一覧</h2>
            <table>
                <tr>
                    <th>サイト名</th>
                    <th>サイトURL</th>
                    <th>登録日時</th>
                    <th>操作</th>
                </tr>
                <tr v-for="site in site_list">
                    <th>{{ site.name }}</th>
                    <th>{{ site.url }}</th>
                    <th>{{ site.created_at }}</th>
                    <th>
                        <button>編集</button>
                        <button>停止</button>
                    </th>
                </tr>
            </table>
        </div>
    </div>
</template>

<style lang="scss" scoped>
#main_wrap{
    max-width: 1020px;
    margin: auto;
    padding: 40px 80px;

    input{
        border: 1px solid #E0E0E0;
        border-radius: 50px;
    }

    button{
        background: #0f0f0f;
        width: 100px;
        padding: 10px;
        color: #fff;
        border-radius: 50px;
    }

    .site_list{
        border: 1px solid #E0E0E0;
        border-radius: 8px;
        margin-top: 30px;

        h2{
            padding: 15px;
            font-weight: bold;
        }

        table{
            width: 100%;

            tr{
                border-bottom: 1px solid #E0E0E0;
            }
        }
    }
}
</style>
