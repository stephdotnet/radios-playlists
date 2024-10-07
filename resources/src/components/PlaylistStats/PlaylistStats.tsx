import {usePlaylistStats} from "@hooks/useGetPlaylistStats";
import PlaylistsStatsSkeleton from "@components/skeletons/PlaylistStatsSkeleton";
import {LineChart} from "@mui/x-charts";

interface PlaylistStatsProps {
  playlistId: number
}

const PlaylistStats = ({ playlistId } : PlaylistStatsProps) => {

  const { isLoading: isStatsLoading, data: dataStats } = usePlaylistStats(
    playlistId,
  );

  return <>
    {isStatsLoading || !dataStats ? (
      <PlaylistsStatsSkeleton/>
    ) : (
      <LineChart
        xAxis={[
          {
            scaleType: 'band',
            valueFormatter: () => '',
            data: Object.keys(dataStats),
            tickInterval: () => false,
          },
          {
            scaleType: 'band',
            valueFormatter: () => '',
            data: Object.keys(dataStats),
            tickInterval: () => false,
          },
        ]}
        series={[
          {
            data: Object.values(dataStats).map((stat) => stat.total),
            showMark: false,
          }
        ]}
        height={200}
        margin={{
          top: 20,
          bottom: 10,
          right: 5,
          left:50,
        }}
      />
    )}
  </>
}

export default PlaylistStats;
