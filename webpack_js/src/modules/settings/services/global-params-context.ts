import React from 'react';
import { GlobalParamsModel } from 'modules/settings/models';

export const GlobalParamsContext = React.createContext<GlobalParamsModel>({} as GlobalParamsModel);
