import React from 'react';
import ReactDOM from 'react-dom';
import { MainScreen } from 'modules/settings/components/main-screen';
import { SettingsContext } from './settings-context';
import { SettingsModel } from 'modules/settings/models';

export class Constructor {
	private readonly root: HTMLElement;
	private readonly params: ISettingsParams;
	private readonly settingsModel: SettingsModel;

	constructor(selector: string, params: ISettingsParams) {
		this.root = document.querySelector(selector);
		if (!this.root) {
			throw `Не удалось инициализировать контейнер: элемент "${selector}" не найден`;
		}

		this.params = params;

		this.settingsModel = new SettingsModel(this.params.settings);

		this.build();
	}

	private build() {
		ReactDOM.render(
			<SettingsContext.Provider value={this.settingsModel}>
				<MainScreen />
			</SettingsContext.Provider>,
			this.root
		);
	}
}
