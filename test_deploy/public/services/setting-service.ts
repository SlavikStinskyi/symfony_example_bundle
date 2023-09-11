import generateRoute from '@/admin-bundle/services/generate-route'
import apiCall, { HttpMethod } from '@/admin-bundle/services/api-call'
import { ApiResponse } from '@/admin-bundle/types/api-response-interface'
import MobileAppSettingInterface, {MobileAppSettingListInterface} from "@/mobile-app-settings-bundle/types/mobile-app-setting-interface";

export default class SettingService {
  public getMobileSetting(id: string): Promise<MobileAppSettingInterface> {
    return apiCall<MobileAppSettingInterface>(
      generateRoute('mobile-app-settings-edit', { id: id })
    ).then(
      response => {
        if (!response.isSuccess()) {
          throw new Error('Error while loading mobile application setting')
        }

        return response.data
      }
    )
  }

  public getMobileSettings(limit: number, offset: number): Promise<MobileAppSettingListInterface> {
    return apiCall<MobileAppSettingListInterface>(
      generateRoute('mobile_app_settings_api_backend_get_list', { limit: limit, offset: offset })
    )
      .then(
        (response: ApiResponse<MobileAppSettingListInterface>) => {
          if (!response.isSuccess()) {
            throw new Error('Error while loading mobile application settings')
          }

          return response.data
        }
      )
  }

  public saveMobileSetting(
    setting: MobileAppSettingInterface
  ): Promise<MobileAppSettingInterface> {
    if ('' === setting.id) {
      return apiCall<MobileAppSettingInterface>(
        generateRoute('mobile_app_settings_api_backend_create'),
        HttpMethod.Post,
        { ...setting }
      ).then(
        response => {
          if (!response.isSuccess()) {
            throw new Error('Error while create mobile application setting')
          }

          return response.data
        }
      )
    }
    return apiCall<MobileAppSettingInterface>(
      generateRoute('mobile_app_settings_api_backend_edit', { id: setting.id }),
      HttpMethod.Put,
      { ...setting }
    ).then(
      response => {
        if (!response.isSuccess()) {
          throw new Error('Error while edit mobile application setting')
        }

        return response.data
      }
    )
  }

  public deleteMobileSetting(id: string): Promise<any> {
    return apiCall<any>(
      generateRoute('mobile_app_settings_api_backend_delete', { id }), HttpMethod.Delete
    ).then(
      response => {
        if (!response.isSuccess()) {
          throw new Error('Error while delete mobile application settings')
        }

        return response
      }
    )
  }

  public copyMobileSetting(id: string): Promise<any> {
    return apiCall<any>(
      generateRoute('mobile_app_settings_api_backend_copy', { id }), HttpMethod.Post
    ).then(
      response => {
        if (!response.isSuccess()) {
          throw new Error('Error while copy mobile application setting')
        }

        return response
      }
    )
  }
}
