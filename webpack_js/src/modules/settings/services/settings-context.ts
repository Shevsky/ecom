import React from 'react';
import { SettingsModel } from 'modules/settings/models/settings';

export const SettingsContext = React.createContext<SettingsModel>({} as SettingsModel);
