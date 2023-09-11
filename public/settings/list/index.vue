<template>
  <layout>
    <template v-slot:layout-header>
      <a-page-header title="Mobile app settings">
        <template slot="extra">
          <a-tooltip placement="bottom" title="Add mobile setting">
            <router-link :disabled="loading" :to="{ name: 'mobile-app-settings-create' }">
              <a-button class="cypress-page-add-button" type="primary">
                Add mobile setting
              </a-button>
            </router-link>
          </a-tooltip>
        </template>
      </a-page-header>
    </template>
    <template v-slot:layout-breadcrumb>
      <a-breadcrumb-item><a v-bind:href="'admin_homepage' | route">Home</a></a-breadcrumb-item>
      <a-breadcrumb-item>Mobile settings</a-breadcrumb-item>
    </template>
    <template v-slot:layout-content>
      <a-table
        :columns=columns
        :data-source=mobileSettings
        :loading="loading"
        :pagination="{ total: count, pageSize: pageSize, pageSizeOptions: pageSizeOptions, showSizeChanger: true }"
        row-key="id"
        size="small"
        tableLayout="fixed"
        @change="handleTableChange"
      >
        <div
          slot="filterDropdown"
          slot-scope="{ setSelectedKeys, selectedKeys, confirm, clearFilters }"
          style="padding: 8px"
        >
          <a-input
            ref="searchInput"
            :value="selectedKeys[0]"
            style="width: 188px; margin-bottom: 8px; display: block;"
            @change="e => setSelectedKeys(e.target.value ? [e.target.value] : [])"
            @pressEnter="() => true"
            class="cypress-page-search-input"
          />
          <a-button
            icon="search"
            size="small"
            style="width: 90px; margin-right: 8px"
            type="primary"
            @click="() => true"
            class="cypress-page-search-button"
          >
            Search
          </a-button>
          <a-button class="cypress-page-reset-button" size="small" style="width: 90px" @click="() => true">
            Reset
          </a-button>
        </div>
        <a-icon
          slot="filterIcon"
          slot-scope="filtered"
          :style="{ color: filtered ? '#108ee9' : undefined }"
          type="search"
          class="cypress-page-filter-button"
        />

        <span slot="page-title" slot-scope="text, record">
          <router-link
            :disabled="loading"
            :to="{ name: 'mobile-app-settings-edit', params: {id: record.id} }"
            class="cypress-page-id-link"
          >
              {{ text }}
          </router-link>
        </span>

        <span slot="enabled" slot-scope="mobileSettings" >
          <a-icon
            v-if="mobileSettings"
            class="game-enabled-icon cypress-game-enabled-icon"
            :type="!mobileSettings.is_enabled ? 'close-circle' : 'check-circle'"
          />
        </span>

        <span slot="action" slot-scope="text, record, index">
          <router-link :disabled="loading" :style="{paddingRight: '10px'}"
                       :to="{ name: 'mobile-app-settings-edit', params: {id: record.id} }">
            <a-tooltip placement="bottom" title="Edit setting">
                <a-icon class="cypress-page-edit-button" :style="{ color: 'edit', cursor: 'pointer' }" type="edit"/>
            </a-tooltip>
          </router-link>

          <router-link :disabled="loading" :style="{paddingRight: '10px'}">
            <a-popconfirm title="Sure to copy mobile application setting?" @confirm="() => onCopy(record)">
                  <a-icon class="cypress-mobile-settings-copy-button" :style="{ color: 'copy', cursor: 'pointer' }" type="copy"/>
              </a-popconfirm>
          </router-link>

          <a-tooltip placement="right" title="Delete setting">
              <a-popconfirm title="Sure to delete?" @confirm="() => onDelete(record, index)">
                  <a-icon class="cypress-page-delete-button" :style="{ color: 'red', cursor: 'pointer' }" type="delete"/>
              </a-popconfirm>
          </a-tooltip>
        </span>
     </a-table>
      <a-modal v-model="visibleConfirmPopup" title="Confirm" @ok="true">
        <p>Sure to delete?</p>
      </a-modal>
      <a-modal v-model="visibleConfirmCopyPopup" title="Confirm" @ok="true">
        <p>Sure to copy mobile setting?</p>
      </a-modal>
    </template>
  </layout>
</template>

<script lang="ts" src="./script.ts"></script>
