import { Input } from 'common/components';
import React, { useMemo, useState } from 'react';
import { IPointsHandbookFinderPoint } from './points-handbook-finder.type';

interface IPointsHandbookFinderTableProps {
	points: Array<IPointsHandbookFinderPoint>;
}

export function PointsHandbookFinderTable({
	points
}: IPointsHandbookFinderTableProps): JSX.Element {
	const [address, setAddress] = useState('');
	const addressRegex = useMemo(
		(): RegExp | null => (!!address ? new RegExp(address, 'i') : null),
		[]
	);

	return (
		<table className="zebra">
			<thead>
				<tr>
					<th>Индекс</th>
					<th>
						Адрес&nbsp;
						<Input type="search" value={address} onChange={setAddress} />
					</th>
				</tr>
			</thead>
			<tbody>
				{points.map(
					(point: IPointsHandbookFinderPoint): JSX.Element => {
						if (!!address && !point.location.address.match(addressRegex)) {
							return <React.Fragment key={point.id} />;
						}

						return (
							<tr key={point.id}>
								<td>{point.index}</td>
								<td>
									<a
										href={`https://maps.yandex.ru/?text=${
											point.location.latitude
										}+${point.location.longitude}`}
										target="_blank"
										rel="nofollow noopener"
									>
										{point.location.address}
									</a>

									{!!point.location.way && (
										<div className="hint">{point.location.way}</div>
									)}
								</td>
							</tr>
						);
					}
				)}
			</tbody>
		</table>
	);
}
