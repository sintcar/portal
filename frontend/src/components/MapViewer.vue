<template>
  <section class="map">
    <div class="map-visual">
      <div class="pin" v-for="point in points" :key="point.name">
        <span class="dot"></span>
        <div class="label">{{ point.name }}</div>
      </div>
    </div>
    <ul class="point-list">
      <li v-for="point in points" :key="point.name">
        <div class="title">{{ point.name }}</div>
        <p>{{ point.description }}</p>
      </li>
    </ul>
  </section>
</template>

<script setup>
defineProps({
  points: {
    type: Array,
    default: () => []
  }
});
</script>

<style scoped>
.map {
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: 16px;
  align-items: start;
}

.map-visual {
  position: relative;
  min-height: 280px;
  border-radius: 16px;
  background: radial-gradient(circle at 30% 30%, #a5b4fc 0, #c7d2fe 20%, #e0e7ff 40%, #f8fafc 100%);
  overflow: hidden;
}

.pin {
  position: relative;
  margin: 16px;
  padding: 8px 12px;
  background: rgba(15, 23, 42, 0.7);
  color: #fff;
  border-radius: 12px;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  box-shadow: 0 12px 32px rgba(15, 23, 42, 0.3);
}

.dot {
  width: 10px;
  height: 10px;
  background: #22d3ee;
  border-radius: 50%;
}

.point-list {
  margin: 0;
  padding: 0;
  list-style: none;
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.title {
  font-weight: 700;
}

p {
  margin: 4px 0 0;
  color: #475569;
}
</style>
