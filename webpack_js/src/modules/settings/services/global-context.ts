import React from 'react';
import { GlobalModel } from 'modules/settings/models';

export const GlobalContext = React.createContext<GlobalModel>({} as GlobalModel);
