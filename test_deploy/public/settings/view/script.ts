import Vue from 'vue';
import Component from 'vue-class-component'
import MobileAppSettingInterface from "@/mobile-app-settings-bundle/types/mobile-app-setting-interface";
import SettingService from "@/mobile-app-settings-bundle/services/setting-service";

@Component
export default class MobileAppSettings extends Vue {
  private isNew = this.$route.params.id == undefined;
  private loading = false;
  private loadingSave = false;
  private operationSystems = [
    { name: 'android' },
    { name: 'ios' }
  ];

  private mobileAppSetting: MobileAppSettingInterface = {
    id: '',
    version: '',
    name: '',
    operation_system: '',
    app_link: '',
    deeplink: '',
    banned_ref_codes: [],
    is_enabled: false,
    app_rate: null,
    updated_at: '',
  };

  private get settingName (): string {
    return this.mobileAppSetting?.name || 'Create new mobile app settings'
  }

  private created (): void {
    this.initPage()
  }

  private async initPage () {
    this.loading = true

    if (this.isNew) {
      this.loading = false
      return
    }

    const response = await (new SettingService).getMobileSetting(this.$route.params.id)
    this.$set(this, 'mobileAppSetting', response)

    this.loading = false
  }

  private async save () {
    const commonValidation = await (this.$refs.pageCommon as any).validate()
    if (!commonValidation) {
      this.$message.error('Data is not valid')
      return false
    }

    this.loadingSave = true
    const response = await (new SettingService).saveMobileSetting(this.mobileAppSetting)
    this.$set(this, 'mobileAppSetting', response)

    this.loadingSave = false
  }
}
