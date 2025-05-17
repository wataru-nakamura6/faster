<script setup>
import { ref } from 'vue';

const siteUrl = ref('');
const message = ref('');
const loading = ref(false);

const registerSite = async () => {
  if (!siteUrl.value) {
    message.value = 'URLを入力してください';
    return;
  }

  loading.value = true;
  message.value = '';

  try {
    const response = await fetch('http://localhost:3001/screenshot', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        url: siteUrl.value,
        site_id: '2'
      })
    });

    const result = await response.json();
    console.log('status:', response.status);
    console.log('result:', result);

    if (response.ok) {
      message.value = '登録が完了しました！';
    } else {
      message.value = `エラー: ${result.error || '不明なエラー'}`;
    }
  } catch (error) {
    message.value = `通信エラー: ${error.message}`;
  } finally {
    loading.value = false;
  }
};
</script>

<template>
  <div>
    <h2>サイトURLを登録</h2>
    <input v-model="siteUrl" type="text" placeholder="https://example.com" />
    <button @click="registerSite" :disabled="loading">登録</button>
    <p>{{ message }}</p>
  </div>
</template>
