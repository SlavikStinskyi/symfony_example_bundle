import Component from 'vue-class-component'
import Vue from 'vue'
import MobileSettingsInterface from '../../types/mobile-app-setting-interface'
import { ProvideReactive } from 'vue-property-decorator'
import apiCall from '@/admin-bundle/services/api-call'
import notify from '@/admin-bundle/services/notify-message'
import generateRoute from "@/admin-bundle/services/generate-route";
import TableDateFilter from '@/pages-bundle/components/table-date-filter.vue';
import SettingService from "@/mobile-app-settings-bundle/services/setting-service";
import PagesInterface from '@/pages-bundle/types/pages-interface';

@Component({
  components: {
    TableDateFilter
  },
  inject: []
})
export default class MobileSettingList extends Vue {
  @ProvideReactive() filterApplied = false;
  public loading = true;
  private count = 0;
  private pageSize = 15;
  private pageSizeOptions: string[] = ['15', '25', '50', '100']
  private latestAppliedFilters: any = {};
  private latestAppliedPagination: any = {
    current: 1,
    pageSize: this.pageSize
  };
  private selectedRowKeys: string[] = [];

  private mobileSettings: MobileSettingsInterface[] = [];
  private columns: any = [];
  private visibleConfirmPopup = false;
  private visibleConfirmCopyPopup = false;
  private apiCall = apiCall;

  get hasSelected () {
    return this.selectedRowKeys.length > 0;
  }

  public async created (): Promise<void> {
    await this.initData();
  }

  public async initData (): Promise<void> {
    await this.loadData();
    this.columns = [
      {
        title: 'Id',
        dataIndex: ['id'],
        key: 'id',
        scopedSlots: {
          customRender: 'page-title'
        },
        ellipsis: true,
        width: '10%'
      },
      {
        title: 'Version',
        dataIndex: ['version'],
        key: 'version',
        scopedSlots: {
          customRender: 'page-title'
        },
        ellipsis: true,
        width: '10%'
      },
      {
        title: 'Name',
        dataIndex: ['name'],
        key: 'name',
        scopedSlots: {
          customRender: 'page-title'
        },
        ellipsis: true,
        width: '10%'
      },
      {
        title: 'Operation system',
        dataIndex: ['operation_system'],
        key: 'operation_system',
        scopedSlots: {
          customRender: 'layout'
        },
        ellipsis: true,
        width: '10%'
      },
      {
        title: 'App link',
        dataIndex: ['app_link'],
        key: 'app_link',
        scopedSlots: {
          customRender: 'layout'
        },
        width: '20%',
        ellipsis: true
      },
      {
        title: 'Deeplink',
        dataIndex: ['deeplink'],
        key: 'deeplink',
        scopedSlots: {
          customRender: 'layout'
        },
        width: '10%'
      },
      {
        title: 'banned ref codes',
        dataIndex: ['banned_ref_codes'],
        key: 'banned_ref_codes',
        scopedSlots: {
          customRender: 'layout'
        },
        width: '10%'
      },
      {
        title: 'Enabled',
        dataIndex: 'is_enabled',
        key: 'is_enabled',
        scopedSlots: {
          customRender: 'enabled'
        },
        width: '10%'
      },
      {
        title: 'App rate',
        dataIndex: 'app_rate',
        key: 'app_rate',
        width: '10%',
        scopedSlots: {
          customRender: 'layout'
        }
      },
      {
        title: 'Updated at',
        dataIndex: 'updated_at',
        key: 'updated_at',
        width: '10%',
        scopedSlots: {
          customRender: 'publish-data'
        }
      },
      {
        title: 'Actions',
        key: 'actions',
        scopedSlots: { customRender: 'action' },
        width: '10%'
      }
    ];
    this.loading = false;
  }

  private async loadData (limit = 15, offset = 0, filters: any = {}): Promise<void> {
    const response = await (new SettingService).getMobileSettings(limit, offset);

    this.mobileSettings = response.mobile_app_settings;
    this.count = response.count;
    return;
  }

  private onSelectChange (selectedRowKeys: string[]) {
    this.selectedRowKeys = selectedRowKeys
  }

  private async handleTableChange (pagination: any, filters: any) {
    this.loading = true
    const offset = (pagination.current * pagination.pageSize) - pagination.pageSize
    const limit = this.pageSize = pagination.pageSize
    await this.loadData(limit, offset, filters)
    this.latestAppliedFilters = filters
    this.latestAppliedPagination = pagination
    this.loading = false
  }

  private showConfirmPopup () {
    this.visibleConfirmPopup = true
  }

  private async onDelete (setting: MobileSettingsInterface, index: number) {
    this.loading = true

    const response = await (new SettingService()).deleteMobileSetting(setting.id);

    this.mobileSettings.splice(index, 1);
    notify(response);
    this.loading = false;
  }

  private async onCopy (setting: MobileSettingsInterface) {
    this.loading = true

    const response = await (new SettingService()).copyMobileSetting(setting.id);

    notify(response);
    this.loading = false;
  }
}
