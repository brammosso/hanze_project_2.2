import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Paths;

public class DataHandlerThread extends Thread {

    private Thread thread = null;
    private String data = null;

    public DataHandlerThread(String data) {
        this.data = data;
    }

    public void run() {
        try {
            // TODO: Change this to write the data correctly to the correct file
            Files.createDirectories(Paths.get("/data/datum"));
            File testFile = new File("/data/datum/id.txt");
            FileOutputStream oFile = new FileOutputStream(testFile, true);
            oFile.write(data.getBytes());
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    public void start() {
        System.out.println("Thread starting");
        if (thread == null) {
            thread = new Thread(this);
            thread.start();
        }
    }

}
