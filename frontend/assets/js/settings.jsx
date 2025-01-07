import React from 'react';
import { createRoot } from 'react-dom/client';
import SettingsPage from './components/SettingsPage';

// 等待 DOM 加载完成
document.addEventListener('DOMContentLoaded', function() {
  const container = document.getElementById('wenprise-catalog-settings');
  if (container) {
    const root = createRoot(container);
    root.render(<SettingsPage />);
  }
});