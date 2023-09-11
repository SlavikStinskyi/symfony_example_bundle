<template>
  <layout>

    <template #layout-header>
      <a-page-header :title="settingName">
        <template #extra>
          <a-button
            type="primary"
            :loading="loading"
            @click="save"
            class="cypress-captcha-save-button"
          >
            Save
          </a-button>
        </template>

      </a-page-header>
    </template>


    <template #layout-breadcrumb>
      <a-breadcrumb-item><a :href="'admin_homepage' | route">Home</a></a-breadcrumb-item>
      <a-breadcrumb-item>
        <router-link :to="{ name: 'mobile-app-settings' }">Mobile settings</router-link>
      </a-breadcrumb-item>
      <a-breadcrumb-item>{{ settingName }}</a-breadcrumb-item>
    </template>


    <template #layout-content>
      <a-form-model ref="form">

        <a-form-model-item
          label="Name"
        >
          <a-input class="cypress-mobile-app-settings-name-input" v-model="mobileAppSetting.name"/>
        </a-form-model-item>

        <a-form-model-item
          label="Version"
          prop="version"
        >
          <a-input class="cypress-mobile-app-settings-name-input" v-model="mobileAppSetting.version"/>
        </a-form-model-item>

        <a-form-model-item
          label="Mobile operation systems"
          prop="operation_system"
        >
          <a-select v-model="mobileAppSetting.operation_system">
            <a-select-option
              v-for="operationSystem in operationSystems"
              :key="operationSystem.name"
              class="cypress-mobile-app-settings-operation-system"
            >
              {{ operationSystem.name }}
            </a-select-option>
          </a-select>
        </a-form-model-item>

        <a-form-model-item
          label="Application link"
          prop="app_link"
        >
          <a-input class="cypress-mobile-app-settings-app-link" v-model="mobileAppSetting.app_link"/>
        </a-form-model-item>

        <a-form-model-item
          label="Deep link"
          prop="deeplink"
        >
          <a-input class="cypress-mobile-app-settings-app-link" v-model="mobileAppSetting.deeplink"/>
        </a-form-model-item>

        <a-form-model-item
          label="Banned referal codes"
          prop="banned_ref_codes"
        >
          <a-select v-model="mobileAppSetting.banned_ref_codes" mode="multiple">
            <a-select-option
              v-for="code in mobileAppSetting.banned_ref_codes"
              :key="code"
              class="cypress-captcha-voter-option"
            >
              {{ code }}
            </a-select-option>
          </a-select>
        </a-form-model-item>

        <a-form-model-item label="Is Enabled" prop="is_enabled">
          <a-checkbox class="cypress-mobile-app-settings-name-input" v-model="mobileAppSetting.is_enabled"/>
        </a-form-model-item>

        <a-form-model-item
          label="Application rate"
          prop="app_rate"
        >
          <a-input class="cypress-mobile-app-settings-name-input" v-model="mobileAppSetting.app_rate"/>
        </a-form-model-item>
      </a-form-model>
    </template>


  </layout>
</template>

<script lang="ts" src="./script.ts"></script>
