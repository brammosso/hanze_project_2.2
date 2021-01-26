
public class Main {

    public static void main(String[] args) {

        // Create a instance of the DataCollector class with port 7790
        DataCollector dc = new DataCollector(args[0], 7790);

        // Start listening to incoming messages from clients (loops forever)
        dc.Listen();

    }
}
