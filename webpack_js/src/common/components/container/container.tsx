import './container.sass';

import React, { PropsWithChildren } from 'react';
import { bem } from 'util/bem';

const classname = bem('container');

export function Container(props: PropsWithChildren<{}>): JSX.Element {
	return <div className={classname()}>{props.children}</div>;
}
