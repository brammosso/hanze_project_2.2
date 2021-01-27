import java.io.File;
import java.time.LocalDate;
import java.time.temporal.ChronoUnit;

public class FolderRemover extends Thread {

    private Thread thread = null;

    public void run() {
        while (true) {
            try {
                Thread.sleep(1000*60*60*24); // Wait 24 hours
                //Thread.sleep(5000); // Wait 24 hours
            } catch (InterruptedException e) {
                e.printStackTrace();
            }
            LocalDate ld = LocalDate.now();
            // Remove all folders older then 4 weeks
            File[] directories = new File("./data/rainfall/").listFiles(File::isDirectory);
            for (File d : directories) {
                LocalDate directoryData = LocalDate.parse(d.getName());
                // Check if a folder is older then 3 days, then delete it
                if (ChronoUnit.DAYS.between(directoryData, ld) > 3) {
                    deleteDir(d);
                }
            }
        }
    }

    public void start() {
        if (thread == null) {
            thread = new Thread(this);
            thread.start();
        }
    }

    void deleteDir(File file) {
        File[] contents = file.listFiles();
        if (contents != null) {
            for (File f : contents) {
                deleteDir(f);
            }
        }
        file.delete();
    }
}
