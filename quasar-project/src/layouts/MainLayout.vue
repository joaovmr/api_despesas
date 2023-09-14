<template>
  <q-layout view="lHh Lpr lFf">
    <q-page-container>
      <router-view :key="$route.fullPath" />
    </q-page-container>
  </q-layout>
</template>

