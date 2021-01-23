public class Main {

    public static void main(String[] args) {
        System.out.println("Data handler started");

        DataHandlerServer dhs = new DataHandlerServer(7790);
        dhs.run();

    }
}
