import {
  BarPlot,
  ChartsXAxis,
  ChartsYAxis,
  LinePlot,
  ResponsiveChartContainer,
} from '@mui/x-charts';
import PlaylistsStatsSkeleton from '@components/skeletons/PlaylistStatsSkeleton';
import { usePlaylistStats } from '@hooks/useGetPlaylistStats';
import { formatDate } from '@utils/system/date';

interface PlaylistStatsProps {
  playlistId: number;
}

const PlaylistStats = ({ playlistId }: PlaylistStatsProps) => {
  const { isLoading: isStatsLoading, data: dataStats } =
    usePlaylistStats(playlistId);

  return (
    <>
      {isStatsLoading || !dataStats ? (
        <PlaylistsStatsSkeleton />
      ) : (
        <ResponsiveChartContainer
          xAxis={[
            {
              scaleType: 'band',
              valueFormatter: (value) => {
                return formatDate(value);
              },
              data: Object.keys(dataStats),
              tickInterval: (value, index) => {
                return (
                  index % Math.round(Object.keys(dataStats).length / 4) === 0
                );
              },
              id: 'date',
            },
          ]}
          yAxis={[{ id: 'count' }, { id: 'total' }]}
          series={[
            {
              type: 'line',
              data: Object.values(dataStats).map((stat) => stat.total),
              color: '#FFF',
              yAxisId: 'total',
            },
            {
              type: 'bar',
              data: Object.values(dataStats).map((stat) => stat.count),
              yAxisId: 'count',
            },
          ]}
          height={200}
        >
          <BarPlot />
          <LinePlot />
          <ChartsXAxis axisId="date" />
          <ChartsYAxis axisId="count" />
          <ChartsYAxis axisId="total" position="right" />
        </ResponsiveChartContainer>
      )}
    </>
  );
};

export default PlaylistStats;
