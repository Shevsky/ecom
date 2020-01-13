import React from 'react';
import { SettingsModel } from 'modules/settings/models';

export const SettingsContext = React.createContext<SettingsModel>({} as SettingsModel);
