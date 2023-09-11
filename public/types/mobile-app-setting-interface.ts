/**
 * @see @/locale-bundle/types/mobile-app-setting-interface.ts
 */
export default interface MobileAppSettingInterface {
  id: string,
  version: string,
  name: string,
  operation_system: string,
  app_link: string,
  deeplink: string,
  banned_ref_codes: string[],
  is_enabled: boolean,
  app_rate: number | null,
  updated_at: string,
}

export interface MobileAppSettingListInterface {
  mobile_app_settings: MobileAppSettingInterface[],
  count: number
}
