<script setup>
import { ref } from 'vue'
import axios from 'axios'

const file = ref(null)
const result = ref(null)
const imageUrl = ref('')

const onFileChange = (e) => {
    file.value = e.target.files[0]
}

const uploadFile = async () => {
    if (!file.value) return

    const formData = new FormData()
    formData.append('file', file.value)

    try {
        const res = await axios.post('/api/upload/image', formData)
        result.value ='アップロード成功'
        imageUrl.value = res.data.result.variants[0].replace(
            'https://imagedelivery.net',
            'https://ad-rip.net/cdn-cgi/imagedelivery'
        )
    } catch (e) {
        result.value = 'アップロード失敗'
    }
}
</script>

<template>
    <Header />
    <div id="main_wrap">
        <input type="file" @change="onFileChange" />
        <button @click="uploadFile">アップロード</button>
        <p v-if="result">{{ result }}</p>
        <div v-if="imageUrl">
            <img :src="imageUrl" alt="アップロードされた画像" />
        </div>
    </div>
</template>

<style lang="scss" scoped>
#main_wrap{
    max-width: 1020px;
    margin: auto;
    padding: 40px 80px;
}
</style>
