import React, { useState, useEffect } from 'react';
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs";
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from "@/components/ui/card";
import { Switch } from "@/components/ui/switch";
import { Label } from "@/components/ui/label";
import { Button } from "@/components/ui/button";
import { AlertCircle } from "lucide-react";
import { Alert, AlertDescription, AlertTitle } from "@/components/ui/alert";

const SettingsPage = () => {
  const [settings, setSettings] = useState({});
  const [saveStatus, setSaveStatus] = useState(null);

  const tabs = {
    price: {
      label: '价格设置',
      title: '价格显示设置',
      description: '控制商品价格的显示方式',
      fields: {
        hide_loop_price: '隐藏商品列表中的价格',
        hide_single_price: '隐藏商品详情页的价格',
        hide_sale_flash: '隐藏促销标签',
      },
    },
    cart: {
      label: '购物车设置',
      title: '购物车按钮设置',
      description: '控制添加到购物车按钮的显示',
      fields: {
        remove_loop_add_to_cart: '移除商品列表中的添加到购物车按钮',
        remove_single_add_to_cart: '移除商品详情页的添加到购物车按钮',
        disable_add_to_cart_action: '禁用添加到购物车功能',
      },
    },
    pages: {
      label: '页面设置',
      title: '页面访问控制',
      description: '控制购物车和结账页面的访问',
      fields: {
        disable_cart_page: '禁用购物车页面',
        disable_checkout_page: '禁用结账页面',
      },
    },
    admin: {
      label: '后台设置',
      title: '后台功能设置',
      description: '控制后台相关功能的显示',
      fields: {
        disable_analytics: '禁用分析功能',
        disable_marketing: '禁用营销功能',
        remove_payments_menu: '移除支付设置菜单',
        remove_marketing_menu: '移除营销菜单',
      },
    },
  };

  useEffect(() => {
    const loadSettings = async () => {
      try {
        const formData = new FormData();
        formData.append('action', 'get_catalog_settings');
        formData.append('nonce', window.wpCatalogSettings.nonce);

        const response = await fetch(window.wpCatalogSettings.ajaxUrl, {
          method: 'POST',
          body: formData,
        });

        const data = await response.json();

        if (data.success) {
          setSettings(data.data);
        } else {
          setSaveStatus({ type: 'error', message: '加载设置失败' });
        }
      } catch (error) {
        setSaveStatus({ type: 'error', message: '加载设置失败' });
      }
    };

    loadSettings();
  }, []);

  const handleSwitchChange = (key, value) => {
    setSettings(prev => ({
      ...prev,
      [key]: value
    }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();

    try {
      const formData = new FormData();
      formData.append('action', 'save_catalog_settings');
      formData.append('nonce', window.wpCatalogSettings.nonce);
      formData.append('settings', JSON.stringify(settings));

      const response = await fetch(window.wpCatalogSettings.ajaxUrl, {
        method: 'POST',
        body: formData,
      });

      const data = await response.json();

      if (data.success) {
        setSaveStatus({ type: 'success', message: data.data });
      } else {
        setSaveStatus({ type: 'error', message: data.data || '保存失败，请重试' });
      }

      setTimeout(() => setSaveStatus(null), 3000);
    } catch (error) {
      setSaveStatus({ type: 'error', message: '保存失败，请重试' });
      setTimeout(() => setSaveStatus(null), 3000);
    }
  };

  return (
      <div className="container p-6">
        <h1 className="text-2xl font-bold mb-6">目录模式设置</h1>

        {saveStatus && (
            <Alert variant={saveStatus.type === 'success' ? 'default' : 'destructive'} className="mb-6">
              <AlertCircle className="h-4 w-4" />
              <AlertTitle>状态</AlertTitle>
              <AlertDescription>
                {saveStatus.message}
              </AlertDescription>
            </Alert>
        )}

        <form onSubmit={handleSubmit}>
          <Tabs defaultValue="price" className="w-full">
            <TabsList className="mb-4">
              {Object.entries(tabs).map(([key, tab]) => (
                  <TabsTrigger key={key} value={key} className="min-w-[100px]">
                    {tab.label}
                  </TabsTrigger>
              ))}
            </TabsList>

            {Object.entries(tabs).map(([key, tab]) => (
                <TabsContent key={key} value={key}>
                  <Card>
                    <CardHeader>
                      <CardTitle>{tab.title}</CardTitle>
                      <CardDescription>{tab.description}</CardDescription>
                    </CardHeader>
                    <CardContent className="space-y-6">
                      {Object.entries(tab.fields).map(([fieldKey, label]) => (
                          <div key={fieldKey} className="flex items-center justify-between space-x-4">
                            <Label htmlFor={fieldKey} className="flex-1">{label}</Label>
                            <Switch
                                id={fieldKey}
                                checked={settings[fieldKey] || false}
                                onCheckedChange={(checked) => handleSwitchChange(fieldKey, checked)}
                            />
                          </div>
                      ))}
                    </CardContent>
                  </Card>
                </TabsContent>
            ))}

            <div className="mt-6">
              <Button type="submit" size="lg">
                保存设置
              </Button>
            </div>
          </Tabs>
        </form>
      </div>
  );
};

export default SettingsPage;